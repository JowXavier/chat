<?php
/**
 * Helper responsável pelo gerenciamento de todas as funções customizadas 
 *
 * @category PHP
 * @package  Chat 
 * @subpackage Functions_Helper
 * @author   Jonathan Xavier Ribeiro <jonathan@wemaker.com.br>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  1.0
 * @link     http://loja.mentenova.com.br
 */

/**
* Função que o json_encode da forma que foi escrito na string  
* 
* @param string $string
* @return string
* 
*/
if (! function_exists('new_json_encode')) {
	function new_json_encode($string) {        
		return preg_replace_callback(          
			'/\\\\u([0-9a-zA-Z]{4})/',          
			function ($matches) {              
				return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');          
			},          
			json_encode($string)      
			);    
	}
}


