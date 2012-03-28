<?php 
class PasswordToolComponent extends Object {
	var $strength = array("Blank","Very Weak","Weak","Medium","Strong","Very Strong");
/**
 * Password generator function
 *
 */
    function generatePassword ($length = 10, $params=null){
        // inicializa variables
        $password = "";
        $i = 0;
        $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
        
        // agrega random
        while ($i < $length){
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            
            if (!strstr($password, $char)) { 
                $password .= $char;
                $i++;
            }
        }
        return $password;
    } 
    
    function checkStrength($pwd) 
	{
		$score = 1;
	
		if (strlen($pwd) < 1)
		{
			return array('score'=>0, 'strength'=>$this->strength[0]); 
		}
		if (strlen($pwd) < 4)
		{
			return array('score'=>1, 'strength'=>$this->strength[1]); 
		}
	
		if (strlen($pwd) >= 8)
		{
			$score++; 
		}
		if (strlen($pwd) >= 12)
		{
			$score++; 
		}
	
		if (preg_match("/[a-z]/", $pwd) && preg_match("/[A-Z]/", $pwd)) 
		{
			$score++; 
		}
		if (preg_match("/[0-9]/", $pwd)) 
		{
			$score++; 
		}
		if (preg_match("/.[!,@,#,$,%,^,&,*,?,_,~,-,£,(,)]/", $pwd)) 
		{
			$score++; 
		}
		return array('score'=>$score, 'strength'=>$this->strength[$score]); 
	}
	
	function scoreToStrength($score){
		if($score < 0 && $score > 5) $score = 0;
		return $this->strength[$score];
	}
    
}
