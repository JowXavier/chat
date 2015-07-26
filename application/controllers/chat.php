<?php
/**
 * Classe responsável pelo gerenciamento do chat 
 *
 * @category PHP
 * @package  Chat 
 * @subpackage Chat
 * @author   Jonathan Xavier Ribeiro <jonathan@wemaker.com.br>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  1.0
 * @link     http://www.mentenova.com/chat
 */
class Chat extends CI_Controller
{
	/**
	* Método construtor  
	* 
	*/ 
    public function __construct()
    {
        parent::__construct();       
        $this->data = array();
    }
	
	/**
    * Método que solicita o 'Nickname' e atualiza ou insere o usuário, caso não tenha nenhum no arquivo json 'arquivo de leitura das mensagens, com o status de online'  
    * 
    * @access public
    * @param string $nickname
    * @param string $type
    * @return void 
    */ 
	public function signin($nickname = null, $type = null) { 
	    $session = array(
            'type' => $type
        ); 

        $this->session->set_userdata($session); 

		if ($this->session->userdata('nickname')) {
			redirect('index');
		}

		if ($this->input->post()) {
	        $rules = array(
	            array(
	                'field' => 'nickname',
	                'label' => 'Nickname',
	                'rules' => 'required'
	            )
	        );
	        
	        $this->form_validation->set_rules($rules);
	        
	        $this->form_validation->set_error_delimiters(
	            '<p class="form_erro">', '</p>'
	        );
	        
	        if ($this->form_validation->run()) {

	            $session = array(
                    'nickname' => $this->input->post('nickname'),
                    'type' => $this->input->post('type')
                ); 

                $this->session->set_userdata($session); 

				$exist = $this->_read();

				if ($this->session->userdata('type') == 'admin') {
					$nickname = '<span style="color: #46955f;"><strong>'.$this->session->userdata('nickname').'</strong></span>';
					$status = '<span style="color: #46955f;">'.$this->config->item('online').'</span>';
				} else {
					$nickname = '<span style="color: #000;">'.$this->session->userdata('nickname').'</span>';
					$status = '<span style="color: #8B8989;">'.$this->config->item('online').'</span>';
				}

				if ($exist) {
					array_push($exist, array(
											'nickname' => $this->session->userdata('nickname'),
											'status' => $this->config->item('online'),
											'message' => '',
											'type' => $this->session->userdata('type')
										)
					);

					$json = new_json_encode(array('chat' => $exist));
				} else {
					$data = array(
							'chat' => array(
									array(
											'nickname' => $this->session->userdata('nickname'), 
											'status' => $this->config->item('online'), 
											'message' => '',
											'type' => $this->session->userdata('type')
										)
									)
							);

					$json = new_json_encode($data);
				}

				$this->_save($json);

				redirect('index');				
			}			
		}

		$this->data['nickname'] = str_replace('-', ' ', $nickname);
		$this->load->view('signin.php', $this->data);
	}

	/**
    * Método que atualiza o arquivo para o status de offline e desloga   
    * 
    * @access public
    * @return void 
    */
    public function logout() {
    	$exist = $this->_read();
    	array_push($exist, array(
								'nickname' => $this->session->userdata('nickname'),
								'status' => $this->config->item('offline'),
								'message' => '',
								'type' => $this->session->userdata('type')
							)
		);

		$json = new_json_encode(array('chat' => $exist));

		$this->_save($json);

		$nickname = $this->session->userdata('nickname');
		$type = $this->session->userdata('type');
 
        $this->session->unset_userdata('nickname');
        $this->session->unset_userdata('write');
        $this->session->unset_userdata('type');

        redirect('/');
    }

	/**
    * Método que busca e insere no arquivo as mensagens  
    * 
    * @access public
    * @return void 
    */ 
	public function index() {		 
		if (! $this->session->userdata('nickname')) {
			redirect(base_url());
		}

		if ($this->input->post()) {
			$exist = $this->_read();

			$equal = array();
			foreach ($exist as $value) {
			 	if (($value->nickname == $this->session->userdata('nickname')) && ($value->message == $this->input->post('write'))) {
			 		$equal[] = 1;
			 	}
			} 

			if ($equal) {
				redirect('index');
			}

			array_push($exist, array(
									'nickname' => $this->session->userdata('nickname'),
									'status' => '',
									'message' => $this->input->post('write'),
									'type' => $this->session->userdata('type') 
								)
			);

			$json = new_json_encode(array('chat' => $exist));

			$this->_save($json);
		}

		$this->data['chat'] = $this->_read();
		$this->load->view('index.php', $this->data);
	}

	/**
    * Método que fica buscando os dados atualizados  
    * 
    * @access public
    * @return void 
    */ 
	public function message() {
		if (! $this->session->userdata('nickname')) {
			redirect(base_url());
		}

		$this->data['chat'] = $this->_read();
		$this->load->view('chat.php', $this->data);
	}

	/**
    * Método que altera a mensagem atual a para removida  
    * 
    * @access public
    * @param string $str
    * @return void 
    */ 
	public function remove($str) {
		$search = base64_decode($str); 
		$file = fopen($this->config->item('json_file'),'r+');
		$string = '';
		if ($file) {
			while(true) {
				$line = fgets($file);
				if ($line==null) break;

				if($search) {
					$string .= str_replace($search, "<small>mensagem removida pelo Administrador</small>", $line);
				} else {
					$string .= $line;
				}
			}

			rewind($file);

			ftruncate($file, 0);
			if (!fwrite($file, $string)) die('Não foi possível exluir essa mensagem.');
			fclose($file);
			redirect('index');
		}
	}

	/**
    * Método que insere no arquivo   
    * 
    * @access public
    * @param string $json
    * @return void 
    */ 
	private function _save($json = null) {
		$file = fopen($this->config->item('json_file'),'w+');
		if ($file) {
			if (!fwrite($file, $json)) die('Não foi possível atualizar o arquivo.');	
			fclose($file);
		}
	}

	/**
    * Método que lê o arquivo  
    * 
    * @access public
    * @return void 
    */ 
	private function _read() {
		$read = file_get_contents($this->config->item('json_file'));
		$reading = json_decode($read);
		if ($reading) {
			return $reading->chat;
		} 
	}
}