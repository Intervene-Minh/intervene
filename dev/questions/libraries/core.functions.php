<?php
/***
@ No- db functions
@PHP - custom
**/

function _remove_basic_tags($text_str){
	  $question_question_spanish=$text_str;
	  ///Single quote and double Quote removed ///
       $question_question_spanish = str_replace('& ldquo;', '&ldquo;', $question_question_spanish);//Left double quote

       $question_question_spanish = str_replace('& rdquo;', '&rdquo;', $question_question_spanish);

        $question_question_spanish = str_replace('& ndash;', '&ndash;', $question_question_spanish);

	  ///
	  // & eacute;
       $question_question_spanish = str_replace('& eacute;', '&eacute;', $question_question_spanish);
	$question_question_spanish = str_replace('& nbsp;', '&nbsp;', $question_question_spanish);
		$question_question_spanish = str_replace('/ images / ', '/images/', $question_question_spanish);
		$question_question_spanish = str_replace('Ol', 'ol', $question_question_spanish);

		$question_question_spanish = str_replace('</ Ol>', '</ol>', $question_question_spanish);
		$question_question_spanish = str_replace('<Li>', '<li>', $question_question_spanish);

		$question_question_spanish = str_replace('<H1>', '<h1>', $question_question_spanish);
		$question_question_spanish = str_replace('<H2>', '<h2>', $question_question_spanish);
		$question_question_spanish = str_replace('<H3>', '<h3>', $question_question_spanish);
		$question_question_spanish = str_replace('<H4>', '<h4>', $question_question_spanish);
		$question_question_spanish = str_replace('<H5>', '<h5>', $question_question_spanish);
		$question_question_spanish = str_replace('<H6>', '<h6>', $question_question_spanish);

		$question_question_spanish = str_replace('</ h1>', '</h1>', $question_question_spanish);
		$question_question_spanish = str_replace('</ h2>', '</h2>', $question_question_spanish);
		$question_question_spanish = str_replace('</ h3>', '</h3>', $question_question_spanish);
		$question_question_spanish = str_replace('</ h4>', '</h4>', $question_question_spanish);
		$question_question_spanish = str_replace('</ h5>', '</h5>', $question_question_spanish);
		$question_question_spanish = str_replace('</ h6>', '</h6>', $question_question_spanish);

		$question_question_spanish = str_replace('<P>', '<p>', $question_question_spanish);
		$question_question_spanish = str_replace('</ p>', '</p>', $question_question_spanish);

		$question_question_spanish = str_replace('<Table>', '<table>', $question_question_spanish);
		$question_question_spanish = str_replace('</ P>', '</p>', $question_question_spanish);

		$question_question_spanish = str_replace('<Tbody>', '<tbody>', $question_question_spanish);

		$question_question_spanish = str_replace('<Tr>', '<tr>', $question_question_spanish);

		$question_question_spanish = str_replace('<Td>', '<td>', $question_question_spanish);

		$question_question_spanish = str_replace('</ td>', '</td>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Tr>', '</tr>', $question_question_spanish);
		

		$question_question_spanish = str_replace('</ Tr>', '</tr>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Tbody>', '</tbody>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Table>', '</table>', $question_question_spanish);

		$question_question_spanish = str_replace('<Ul>', '<ul>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Li>', '</li>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Ul>', '</ul>', $question_question_spanish);

		$question_question_spanish = str_replace('<Blockquote>', '<blockquote>', $question_question_spanish);

		$question_question_spanish = str_replace('</ Blockquote>', '</blockquote>', $question_question_spanish);

		$question_question_spanish = str_replace('<P>', '<p>', $question_question_spanish);

		$question_question_spanish = str_replace('</ strong>', '</strong>', $question_question_spanish);

		$question_question_spanish = str_replace('</ em>', '</em>', $question_question_spanish);

		$question_question_spanish = str_replace('</ p>', '</p>', $question_question_spanish);
		
		$question_question_spanish = str_replace('</ Li>', '</li>', $question_question_spanish);

		$question_question_spanish = str_replace('</ span>', '</span>', $question_question_spanish);

		$question_question_spanish = str_replace('> </', '></', $question_question_spanish);

		$question_question_spanish = str_replace('>  </', '></', $question_question_spanish);

		$question_question_spanish = str_replace('<img src = "datos: imagen / png;', '<img src ="data:image/png;', $question_question_spanish);

		$question_question_spanish = str_replace('<img src = "datos: imagen / jpg;', '<img src ="data:image/jpg;', $question_question_spanish);

		$question_question_spanish = str_replace('<img src = "datos: imagen / jpge;', '<img src ="data:image/jpge;', $question_question_spanish);
		//////////////
		return $question_question_spanish; //Str

}



?>