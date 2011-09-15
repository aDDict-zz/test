import java.io.*;
import java.net.*;
import java.sql.*;
import java.util.*;
import java.nio.charset.*;
import java.nio.*;
import java.util.logging.Level;
import java.util.regex.*; 
import java.util.*;

import javax.xml.parsers.*;
import org.xml.sax.*;
import org.xml.sax.helpers.*;
import org.w3c.dom.*;

import freemarker.cache.*;
import freemarker.core.*;
import freemarker.debug.*;
import freemarker.log.*;
import freemarker.template.*;


class MySql{
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
			new ErrorLogger("MySql", Level.SEVERE, "MySql connect XML failed: " + e);			
		}
	}
	
	public void connect(){
		try {	        	            
	        String driverName = "com.mysql.jdbc.Driver"; 
	        Class.forName(driverName);	    	        	        
	        String url = "jdbc:mysql://" + this.host +  "/" + this.dataBase;
	        this.connection = DriverManager.getConnection(url + "?requireSSL=false&useUnicode=true&characterEncoding=UTF-8", this.user, this.psw);	        	        
	    } catch (ClassNotFoundException e) {
	        new ErrorLogger("MySql", Level.SEVERE, "MySql driver not found: " + e);	        
	    } catch (SQLException e) {
	    	new ErrorLogger("MySql", Level.SEVERE, "MySql connect error: " + e);	        
	    }
	}
	
	public void disconnect(){
		try {
			this.connection.close();	
	    } catch (SQLException e) {
	        new ErrorLogger("MySql", Level.SEVERE, "MySql disconnect error: " + e);
	    }		
	}
	
	public ResultSet executeSelect(String query){
		Statement stmt = null;
		ResultSet rs = null;
		try {	        
	        stmt = this.connection.createStatement();
	        rs = stmt.executeQuery(query);	        
	    } catch (SQLException e) {
	    	new ErrorLogger("MySql", Level.SEVERE, "MySql error: " + e + " Query: " + query);
	    }
	    return rs;		
	}
	
	public void executeInsert(String query){
		try {
	        Statement stmt = connection.createStatement();	    	        	        	        	        
	        stmt.executeUpdate(query);
	    } catch (SQLException e) {
	    	new ErrorLogger("MySql", Level.SEVERE, "MySql error: " + e + " Query: " + query);
	    }
	}
}


class RSS{
	private String   xmlVersion  = null;
	private String   rssVersion  = null;
	private String   encoding    = null;
	private String   title       = null;
	private String   link     	 = null;
	private String   description = null;
	private String   language    = null;
	private int      nrItems     = 0;
	private RSSItems items[]     = null;
	private Document xml = null;
	
	public RSS(Document doc) throws UnsupportedEncodingException{
		this.xml = doc;
		this.rssVersion = this.xml.getDocumentElement().getAttribute("version");
		this.xmlVersion = this.xml.getXmlVersion();
		this.encoding = this.xml.getXmlEncoding();
		this.title = this.xml.getElementsByTagName("title").item(0).getTextContent();
		this.link = this.xml.getElementsByTagName("link").item(0).getTextContent();
		this.description = this.xml.getElementsByTagName("description").item(0).getTextContent();
		this.language = this.xml.getElementsByTagName("language").item(0).getTextContent();		
		this.parseRss();
	}
	
	private void parseRss() throws UnsupportedEncodingException{
		this.nrItems = this.xml.getElementsByTagName("item").getLength();
		this.items = new RSSItems[nrItems];
		for(int i=0;i<this.xml.getElementsByTagName("item").getLength();i++){
			RSSItems rssItems = new RSSItems(this.xml.getElementsByTagName("item").item(i));
	    	this.items[i] = rssItems.getItemsChildNodes();		    	
		}
		
	}
	
	public RSSItems[] getItems(){
		return this.items;
	} 
	
	public String getRSSVersion(){
		return this.rssVersion;
	}
	
	public String getXMLVersion(){
		return this.xmlVersion;
	}
	
	public String getXMLEnconding(){
		return this.encoding;
	}
	
	public String getRSSTitle(){
		return this.title;
	}
	
	public String getRSSLink(){
		return this.link;
	}
	
	public String getRSSDescription(){
		return this.description;
	}
	
	public String getRSSLaguage(){
		return this.language;
	}
	
}

class TestDB extends TimerTask {
	private String url = null;
	private int counter = 0;
	private int rssId = 0;
	public TestDB(){		
	}
	
	public void run(){
		try{
			URL u = new URL("http://192.168.0.1/test/test.php");
		   BufferedReader in = new BufferedReader(new InputStreamReader(u.openStream()));
		   String str = "";
		   while((str = in.readLine())!=null){
		   		System.out.println(str);
		   }
		   in.close();	
	    } catch (Exception e) {	        
	        System.out.println(e);
	    } 	        	
	}
}

class WriteRSS{
	private String rssTemplateDir = "c:/work/Hirek.hu/motor/templates/";
	private String rssWebDir = "c:/";
	private String rssFileName = "test.rss";
	
	private String title = "";
	private String link = "";
	private String description = "";
	
	private String language = "hu-HU";
	private String copyright = "";
	private String managingEditor = "szerkeszto@hirek.hu";
	private String webMaster = "webmester@hire.hu";
	private String pubDate = "";
	private String lastBuildDate = "";
	private String category = "";
	private String generator = "Hirek.hu";
	private String docs = "http://blogs.law.harvard.edu/tech/rss";
	private String ttl = "60";
	
	private ArrayList items = null;
	
	private String[] days = {"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"};
	
	private String getDay(int day){
		return this.days[day];
	}
	
	private String getDate(){
		Calendar cal = new GregorianCalendar();
		int hour12 = cal.get(Calendar.HOUR);            // 0..11
		int hour24 = cal.get(Calendar.HOUR_OF_DAY);     // 0..23
		int min = cal.get(Calendar.MINUTE);             // 0..59
		int sec = cal.get(Calendar.SECOND);             // 0..59
		int ms = cal.get(Calendar.MILLISECOND);         // 0..999
		int ampm = cal.get(Calendar.AM_PM);             // 0=AM, 1=PM
		    
		int year = cal.get(Calendar.YEAR);             // 2002
		int month = cal.get(Calendar.MONTH);           // 0=Jan, 1=Feb, ...
		int day = cal.get(Calendar.DAY_OF_MONTH);      // 1...
		int dayOfWeek = cal.get(Calendar.DAY_OF_WEEK); // 1=Sunday, 2=Monday,
    	TimeZone timeZone = cal.getTimeZone();	
    	return this.getDay(dayOfWeek)+", "+day+" "+month+" "+year+" "+hour24+":"+min+":"+sec+" "+timeZone.getDisplayName(true, TimeZone.SHORT);
	}
	
	public WriteRSS(){		
	}
	
	public void	setTitle(String title){
		this.title = title;
	}
	
	public void setLink(String link){
		this.link = link;
	}
	
	public void setDescription(String description){
		this.description = description;
	}
	
	public void setLanguage(String language){
		this.language = language;
	}
	
	public void setCopyright(String copyright){
		this.copyright = copyright;
	}
	
	public void setManagingEditor(String managingEditor){
		this.managingEditor = managingEditor;
	}
	
	public void setWebMaster(String webMaster){
		this.webMaster = webMaster;		
	}
	
	public void setPubData(String pubDate){
		this.pubDate = pubDate;
	}
	
	public void setLastBuildDate(String lastBuildDate){
		this.lastBuildDate = lastBuildDate;
	}
	
	public void setCategory(String category){
		this.category = category;
	}
	
	public void setGenerator(String generator){
		this.generator = generator;				
	}
	
	public void setDocs(String docs){
		this.docs = docs;
	}
	
	public void setTtl(String ttl){
		this.ttl = ttl;
	}
	
	public void setRSSItems(ArrayList items){						
		this.items = items;					
	}
	
	public void writeRSS() throws Exception{
		String templateDir = "c:/work/Hirek.hu/motor/templates/";
		String webDir = "c:/";
			
		Configuration cfg = new Configuration();
		cfg.setDirectoryForTemplateLoading(new File(this.rssTemplateDir));
		cfg.setObjectWrapper(ObjectWrapper.BEANS_WRAPPER);
		
		Map root = new HashMap();						
		root.put("title", this.title);
		root.put("link", this.link);
		root.put("description", this.description);
		
		root.put("language", this.language);			
		root.put("copyright", this.copyright);
		root.put("managingEditor", this.managingEditor);
		root.put("webMaster", this.webMaster);
		root.put("pubDate", this.getDate());
		root.put("lastBuildDate", this.getDate());
		root.put("category", this.category);
		root.put("generator", this.generator);
		root.put("docs", this.docs);
		root.put("ttl", this.ttl);
		
				
		root.put("items", this.items);
					
		Template temp = cfg.getTemplate("templates.rss");  
		BufferedWriter out = new BufferedWriter(new FileWriter(this.rssWebDir + this.rssFileName));
		temp.process(root, out);
		        
		out.close();	
	}
}

class Top{
	public Top(){
	}
	
	
}
public class Test{				
	public static void main(String[] args) {
		
		try{			          			   			   	
					
			/*URL url = new URL("http://klubradio.hu/");
			BufferedReader in = new BufferedReader(new InputStreamReader(url.openStream()));
			//BufferedReader in = new BufferedReader(new FileReader("biztonsagportal.hu"));
			        
			CharSequence ch = null;
			String str;
			Pattern pattern = Pattern.compile("<a href=\"(.*?)\" style=\"color:#4B4A4A;\"><b>(.*?)</b></a>");
			BufferedWriter out = new BufferedWriter(new FileWriter(" .hu.txt", true));
			int nr = 1;
			while ((str = in.readLine()) != null) {								
				Matcher matcher = pattern.matcher(str);
				while(matcher.find()){		    				    											
					System.out.println(matcher.group(1) + "- " + matcher.group(2));	
					out.write(nr + ". " + matcher.group(1) + "- " + matcher.group(2) + "\r\n");				
					nr ++;
				}
				//if(nr>=100)	break;	 
			}
			in.close();
			out.close();
			*/
			/*MySql sql = new MySql();
			sql.connect();
			
			java.util.Date date = new java.util.Date();
			Calendar cal = new GregorianCalendar();
			
			cal.setTimeInMillis(date.getTime()-14400000); //top4
			//cal.setTimeInMillis(date.getTime()-43200000); //top12
			//cal.setTimeInMillis(date.getTime()-86400000); //top24
			//cal.setTimeInMillis(date.getTime()-604800000); //top7*24
				
						
			int hour24 = cal.get(Calendar.HOUR_OF_DAY);     // 0..23
			int min = cal.get(Calendar.MINUTE);             // 0..59
			int sec = cal.get(Calendar.SECOND);             // 0..59						    
			int year = cal.get(Calendar.YEAR);             // 2002
			int month = cal.get(Calendar.MONTH);           // 0=Jan, 1=Feb, ...
			int day = cal.get(Calendar.DAY_OF_MONTH);      // 1...		
	    	
	    	System.out.println(year+"-"+month+"-"+day+" "+hour24+":"+min+":"+sec+"  " + date.getTime());
	    	ResultSet rs = sql.executeSelect("Select news_id, news_title, agency_name, news_lead From stat_news Left Join news On id=news_id Where stat_news.dadd>='" + year+"-"+month+"-"+day+" "+hour24+":"+min+":"+sec + "' Order by click_nr Limit 50");
	    	ArrayList items = new ArrayList();
	    	while(rs.next()){
	    		System.out.println(rs.getString("news_title"));
	    		items.add(new RSSItems(rs.getString("news_title") + " - " + rs.getString("agency_name") , "http://www.hirek.hu/p/r/" + rs.getInt("news_id"), ""));
	    	}
			sql.disconnect();
			
			WriteRSS ws = new WriteRSS();
			ws.setTitle("Hirek.hu  - Top 4 ora");
			ws.setLink("http://www.hirek.hu");
			ws.setCategory("Top 4 ora");
			ws.setCopyright("Hirek.hu 2006");
			
			ws.setRSSItems(items);
			ws.writeRSS();
			*/
			Calendar cal = GregorianCalendar.getInstance();
			
			System.out.println(cal.get(Calendar.YEAR) + " - " +  (cal.get(Calendar.MONTH)+1));
	    } catch (Exception e) {
	        System.out.println(e);
	    } 	    
    }                
}