//package MySql;

import java.sql.*;
import javax.xml.parsers.*;
import org.xml.sax.*;
import org.xml.sax.helpers.*;
import org.w3c.dom.*;


public class MySql{
	private String host 	= null;
	private String user 	= null;
	private String psw  	= null;
	private String dataBase = null;
	private Connection connection = null;
	
	public MySql(){
		try {
			DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
			Document doc = factory.newDocumentBuilder().parse("connection.xml");
			NodeList nodeList = null;
			nodeList = doc.getElementsByTagName("host");
			this.host = nodeList.item(0).getTextContent();
			nodeList = doc.getElementsByTagName("user");
			this.user = nodeList.item(0).getTextContent();
			nodeList = doc.getElementsByTagName("psw");
			this.psw = nodeList.item(0).getTextContent();
			nodeList = doc.getElementsByTagName("database");
			this.dataBase = nodeList.item(0).getTextContent();			
		}catch(Exception e){
			//new ErrorLogger("MySql", Level.SEVERE, "MySql connect XML failed: " + e);
		}
	}
	
	public void connect(){
		try {	        	            
	        String driverName = "com.mysql.jdbc.Driver"; 
	        Class.forName(driverName);	    	        	        
	        String url = "jdbc:mysql://" + this.host +  "/" + this.dataBase;
	        this.connection = DriverManager.getConnection(url + "?requireSSL=false&useUnicode=true&characterEncoding=UTF-8", this.user, this.psw);
	    } catch (ClassNotFoundException e) {
	        //new ErrorLogger("MySql", Level.SEVERE, "MySql driver not found: " + e);
	        System.out.println(e);
	    } catch (SQLException e) {
	        //new ErrorLogger("MySql", Level.SEVERE, "MySql connect error: " + e);
	        System.out.println(e);
	    }
	}
	
	public void disconnect(){
		try {
			this.connection.close();	
	    } catch (SQLException e) {
	         System.out.println(e);
	         //new ErrorLogger("MySql", Level.SEVERE, "MySql disconnect error: " + e);
	    }		
	}
	
	public ResultSet executeSelect(String query){
		Statement stmt = null;
		ResultSet rs = null;
		try {	        
	        stmt = this.connection.createStatement();
	        rs = stmt.executeQuery(query);	        
	    } catch (SQLException e) {
	    	System.out.println(e);
	    	//new ErrorLogger("MySql", Level.SEVERE, "MySql error: " + e + " Query: " + query);	
	    }
	    return rs;		
	}
	
	public void executeInsert(String query){
		try {
	        Statement stmt = connection.createStatement();	    	        	        
	        stmt.executeUpdate(query);
	    } catch (SQLException e) {
	    	//System.out.println(e);
	    	//new ErrorLogger("MySql", Level.SEVERE, "MySql error: " + e + " Query: " + query);
	    }
	}
	
	public void executeSQL(String query){
		try {
	        Statement stmt = connection.createStatement();	    	        	        
	        stmt.execute(query);
	    } catch (SQLException e) {
	    	System.out.println(e);	    	
	    }
	}
}