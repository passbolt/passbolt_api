$.passbolt.password = {
	passwordClear:null,
	passwordEncrypted:null,
	getVerdictColors:function(){
		var colors = Array();
		colors['very weak'] = "aa0033";
		colors['weak'] = "ff1313";
		colors['fair'] = "ffcc33";
		colors['strong'] = "37ff37";
		colors['very strong'] = "008000";
		
		return colors;
	},
	/**
	 * Gives the corresponding verdict according to a score
	 * @param score : the score for which to get the verdict
	 * @returns the verdict
	 */
	getVerdict:function(score){
		var strVerdict = "weak";
		
		if(score < 20)
		   strVerdict = "very weak";
		else if (score >=20  && score < 40)
		   strVerdict = "weak";
		else if (score >= 40 && score < 60)
		   strVerdict = "fair";
		else if (score >= 60 && score < 80)
		   strVerdict = "strong";
		else
		   strVerdict = "very strong";
		
		return strVerdict;
	},
	/**
	 * Calculates the strength of a password and return it (score varies from 0 to 100)
	 * @param passwd : the password from which calculate the score
	 * @returns the score
	 */
	getStrength:function(passwd){
		var intScore   = 0;
		var strLog     = "";
			
		// PASSWORD LENGTH
		if (passwd.length<5)                         // length 4 or less
		{
			intScore += 6;
		}
		else if (passwd.length>4 && passwd.length<8) // length between 5 and 7
		{
			intScore += 12;
		}
		else if (passwd.length>7 && passwd.length<16)// length between 8 and 15
		{
			intScore += 25;
		}
		else if (passwd.length>15)                    // length 16 or more
		{
			intScore += 35;
		}
		// LETTERS (Not exactly implemented as dictacted above because of my limited understanding of Regex)
		if (passwd.match(/[a-z]/))                              // [verified] at least one lower case letter
		{
			intScore += 5;
		}
		if (passwd.match(/[A-Z]/))                              // [verified] at least one upper case letter
		{
			intScore += 10;
		}
		// NUMBERS
		if (passwd.match(/\d+/))                                 // [verified] at least one number
		{
			intScore += 10;
		}
		if (passwd.match(/(.*[0-9].*[0-9].*[0-9])/))             // [verified] at least three numbers
		{
			intScore += 10;
		}
		// SPECIAL CHAR
		if (passwd.match(/.[!,@,#,$,%,^,&,*,?,_,~]/))            // [verified] at least one special character
		{
			intScore += 10;
		}
		// [verified] at least two special characters
		if (passwd.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/))
		{
			intScore += 10;
		}
		// COMBOS
		if (passwd.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))        // [verified] both upper and lower case
		{
			intScore += 5;
		}
		if (passwd.match(/([a-zA-Z])/) && passwd.match(/([0-9])/)) // [verified] both letters and numbers
		{
			intScore  += 5;
		}
		// [verified] letters, numbers, and special characters
		if (passwd.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[a-zA-Z0-9])/))
			intScore += 5;
		
		return (intScore > 100 ? 100 : intScore);
	}
};