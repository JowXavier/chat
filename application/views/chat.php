<?php
foreach ($chat as $value) {	
	if ($value->status) {
		if ($value->type == 'admin') {			
			echo '<span style="color: #46955f;"><strong>'.$value->nickname.' '.$this->config->item('its').' '.$value->status.'</strong></span><br />';
		} else {
			echo '<span style="color: #000;">'.$value->nickname.' '.$this->config->item('its').'</span> <span style="color: #8B8989;">'.$value->status.'</span><br />';
		}
	}	

	if ($value->message) {
		if (($this->session->userdata('type') == 'admin') && ($value->nickname != $this->session->userdata('nickname')) && ($value->message != '<small>mensagem removida pelo Administrador</small>')) {
			echo '<span style="color: #000;">'.$value->nickname.' '.$this->config->item('say').':</span> <span style="color: #8B8989;">'.$value->message.'</span> <a href="'.base_url().'remover/'.base64_encode($value->message).'" style="color: #FF0000;"><i class="fa fa-times"></i></a><br />';
		} else {
			if ($value->type == 'admin') {
				echo '<span style="color: #46955f;"><strong>'.$value->nickname.' '.$this->config->item('say').': '.$value->message.'</strong></span><br />';
			} else {
				echo '<span style="color: #000;">'.$value->nickname.' '.$this->config->item('say').':</span> <span style="color: #8B8989;">'.$value->message.'</span><br />';
			}			
		}		
	}
}