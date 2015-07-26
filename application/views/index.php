<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Bootstrap, from Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
	<?php echo link_tag('assets/css/bootstrap.css'); ?>

    <style type="text/css">
		body {
			/*padding-top: 60px;
			padding-bottom: 40px;*/
		}
		
		.sidebar-nav {
			padding: 9px 0;
		}
		
		#chat{
			height: 800px;
		}
		
		#chat-area-people{
			height: 569px;
		}

		#chat-area{
			overflow:auto;
			height: 450px
		}
		
		#chat-people{
			overflow:auto;
			height: 540px
		}
		
		#chat-write, textarea{
			width: 100%
		}
    </style>

	<?php echo link_tag('assets/css/bootstrap-responsive.css'); ?>
	<?php echo link_tag('assets/font-awesome/css/font-awesome.min.css'); ?>
	
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="shortcut icon" href="ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">	
  </head>
  <body>  
    <div class="container">
		<div class="row">
			<div class="">
				<div class="hero-unit">
					<div id="chat-area">
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
									if (($this->session->userdata('type') == 'admin') && ($value->nickname != $this->session->userdata('nickname')) && ($value->type == 'site') && ($value->message != '<small>mensagem removida pelo Administrador</small>')) {
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
						?>
					</div>
					<br />
					<div id="chat-write">
						<?php echo form_open('index', array('id' => 'form_chat'));
					        echo form_error('write');
					        echo form_input(array(
					            'id' => 'write',
					            'name' => 'write',
					            'value' => set_value('write', ''),
					            'placeholder' => 'Escreva uma mensagem',
					            'style' => 'width: 100%;height:50px;',
					            'maxlength' => 500
					        ));

							$data = array(
								'name' => 'button',
								'id' => 'button',
								'type' => 'submit',
								'content' => 'Enviar',
								'class' => 'btn btn-primary'
							);

							echo form_button($data);
							echo nbs(3);							
							echo anchor('sair', 'Sair do chat', array('title' => 'Sair do Chat')); 
						echo form_close(); ?>
					</div>					
				</div>
			</div>
		</div>
    </div>

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url('assets/js/jquery.js'); ?>"></script>
  	<script>
	    $(document).ready(function() {
			$('#form_chat').keydown(function() {
				var key = e.which;
				if (key == 13) {
					$('#form_chat').submit();
				}
			});

			var tempo = window.setInterval(refresh, 1000);
		});

		function refresh()
		{
			$('#chat-area').load("<?php echo base_url(); ?>mensagem");
		}
        
        (function() {
             var run = function() {  
                setTimeout(function()
                {
                    var id = document.getElementById('chat-area');
                    id.scrollTop = id.scrollHeight;
                }, 10);   
            };
            run();
        }());
    </script>
  </body>
</html>
