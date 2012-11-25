steal('./base64',function(Base64){
	
	var HttpURLConnection = java.net.HttpURLConnection,
		JStr = java.lang.String,
		URL = java.net.URL,
		DataOutputStream = java.io.DataOutputStream,
		DataInputStream = java.io.DataInputStream,
		InputStreamReader = java.io.InputStreamReader,
		BufferedReader = java.io.BufferedReader;
	
	
	return function(request, success, error){
		
		
	
		var urlParameters = request.data;
		var myurl = new URL(request.url); 
	
		con = myurl.openConnection();
		con.setRequestMethod("POST");
		
		con.setRequestProperty("Content-length", ""+urlParameters.length); 
		con.setRequestProperty("Content-Type","application/json");
		
		if(request.username){
			var auth = "Basic "+ Base64.encode(request.username +":"+request.password);
			con.setRequestProperty("Authorization", auth);
		}
		
		 
		con.setDoOutput(true); 
		con.setDoInput(true); 
		
		var output = new DataOutputStream(con.getOutputStream());  
		
		output.writeBytes(urlParameters);
		
		output.close();
		
		
		var rd = new BufferedReader(new InputStreamReader(con.getInputStream()));
		var result = "";
		while ((line = rd.readLine()) != null) {
		    result += line;
		}
		
		rd.close(); 
		return result;
		
	}
	

	
})()

	

