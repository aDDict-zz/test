import java.io.*;
import java.net.*;
import java.sql.*;
import java.util.*;
import java.util.logging.Level;
import java.util.regex.*; 

import javax.xml.parsers.*;
import org.xml.sax.*;
import org.xml.sax.helpers.*;
import org.w3c.dom.*;

import freemarker.cache.*;
import freemarker.core.*;
import freemarker.debug.*;
import freemarker.log.*;
import freemarker.template.*;

import MySql.*;



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
	
	public RSS(Document doc){		
		if(doc!=null){
			this.xml = doc;
			if(this.xml.getDocumentElement().getAttribute("version")!=null)
				this.rssVersion = this.xml.getDocumentElement().getAttribute("version");
			if(this.xml.getXmlVersion()!=null)	
				this.xmlVersion = this.xml.getXmlVersion();
			if(this.xml.getXmlEncoding()!=null)	
				this.encoding = this.xml.getXmlEncoding();
			if(this.xml.getElementsByTagName("title").item(0)!=null)	
				this.title = this.xml.getElementsByTagName("title").item(0).getTextContent();
			if(this.xml.getElementsByTagName("link").item(0)!=null)
				this.link = this.xml.getElementsByTagName("link").item(0).getTextContent();			
			if(this.xml.getElementsByTagName("description").item(0)!=null)				
				this.description = this.xml.getElementsByTagName("description").item(0).getTextContent();						
			if(this.xml.getElementsByTagName("language").item(0)!=null)	
				this.language = this.xml.getElementsByTagName("language").item(0).getTextContent();
			
		}
		this.parseRss();
	}
	
	private void parseRss(){
		this.nrItems = this.xml.getElementsByTagName("item").getLength();
		this.items = new RSSItems[nrItems];
		for(int i=0;i<this.xml.getElementsByTagName("item").getLength();i++){
			try{
				if(this.xml.getElementsByTagName("item").item(i)!=null){
					RSSItems rssItems = new RSSItems(this.xml.getElementsByTagName("item").item(i));
			    	this.items[i] = rssItems.getItemsChildNodes();		    	
			    }	
	    	}catch(Exception e){
	    		System.out.println(e);
	    		//new ErrorLogger("RSS", Level.SEVERE, "RSS parse error: " + e + " Feed: " + this.title);
	    	}
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

class WriteRSS{
		
	private String rssTemplateDir = "/var/www/hirek.hu/www/lucene/hirek/templates/";
	private String rssWebDir = "/var/www/hirek.hu/www/www/rss/";
	private String rssWebDirCT = "/var/www/hirek.hu/www/www/rssct/";
    public int ct = 0;
	
	private String rssFileName = "";
	
	private String title = "";
	private String link = "";
	private String description = "";
	
	private String language = "hu-HU";
	private String copyright = "";
	private String managingEditor = "szerkeszto@hirek.hu (Szerkesztő)";
	private String webMaster = "webmester@hirek.hu (Webmester)";
	private String pubDate = "";
	private String lastBuildDate = "";
	private String category = "";
	private String generator = "Hirek.hu";
	private String docs = "http://blogs.law.harvard.edu/tech/rss";
	private String ttl = "60";
	
	private ArrayList items = null;
	
	private String[] days = {"Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"};
	private String[] months = {"Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct" , "Nov", "Dec"};
	
	private String getDay(int day){
		return this.days[day-1];
	}
	private String getMonth(int month){
		return this.months[month];
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

		String offs = timeZone.getDisplayName(true, TimeZone.SHORT) == "CEST" ? "+0200" : "+0100";
    	return this.getDay(dayOfWeek)+", "+day+" "+this.getMonth(month)+" "+year+" "+hour24+":"+min+":"+(sec < 10 ? "0" : "") +sec+" " + offs;
    	//return this.getDay(dayOfWeek)+", "+day+" "+this.getMonth(month)+" "+year+" "+hour24+":"+min+":"+sec+" "+timeZone.getDisplayName(true, TimeZone.SHORT);
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
	
	public void setRSSFileName(String rssFileName){
		this.rssFileName = rssFileName;
	}
	
	public void writeRSS() throws Exception{					
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
		//BufferedWriter out = new BufferedWriter(new FileWriter(this.rssWebDir + this.rssFileName));
        OutputStreamWriter out; 
        if (this.ct == 1) {
    		out = new OutputStreamWriter(new FileOutputStream(this.rssWebDirCT + this.rssFileName), "UTF-8");
        } else {
    		out = new OutputStreamWriter(new FileOutputStream(this.rssWebDir + this.rssFileName), "UTF-8");
        }
		
		temp.process(root, out);
		        
		out.close();	
	}
}

class WriteTopNews extends TimerTask{			
	public WriteTopNews(){
	}		
	
	public void run(){
		try{
			MySql sql = new MySql();
			sql.connect();
			
			for(int i=0;i<4;i++){
				WriteRSS ws = new WriteRSS();			
				ws.setLink("http://www.hirek.hu");			
                 
        		Calendar cal = new GregorianCalendar();	
				ws.setCopyright("Hirek.hu " + cal.get(Calendar.YEAR));
								
				MyDate md = new MyDate();
					
				switch(i){
					case 0:					
						ws.setTitle("Hirek.hu - Top 4 ora");
						ws.setCategory("Top 4 ora");
						ws.setRSSFileName("top4.xml");
						md.setTop4Time();
						break;
					case 1:
						ws.setTitle("Hirek.hu - Top 12 ora");
						ws.setCategory("Top 12 ora");
						ws.setRSSFileName("top12.xml");
						md.setTop12Time();
						break;	
					case 2:
						ws.setTitle("Hirek.hu - Top 24 ora");
						ws.setCategory("Top 24 ora");
						ws.setRSSFileName("top24.xml");
						md.setTop24Time();
						break;		
					case 3:
						ws.setTitle("Hirek.hu - Top 50");
						ws.setCategory("Top 50");
						ws.setRSSFileName("top50.xml");
						md.setTop7x24Time();
						break;		
				}
											    		    
			    
			    ResultSet rs = sql.executeSelect("Select news_id, news_title, agency_name, news_lead From stat_news Left Join news On id=news_id Where stat_news.dadd>='" + md.getDate() + "' Order by click_nr Limit 50");
			    ArrayList items = new ArrayList();
			    while(rs.next()){		    	
			    	items.add(new RSSItems(rs.getString("news_title") + " - " + rs.getString("agency_name") , "http://www.hirek.hu/p/r/" + rs.getInt("news_id"), rs.getString("news_lead").replaceAll("<", "&lt;").replaceAll(">", "&gt;")));
			    }			
							
				ws.setRSSItems(items);
				ws.writeRSS();	
			}
			sql.disconnect();
		}catch(Exception e){
			System.out.println("WriteTopNews error: " + e);
		}
	}
}


class WriteFreshNews extends TimerTask{
	
	public WriteFreshNews(){
	}		
	
	public void run(){
//		String getFeedCategoriesQuery = "Select cat_id, cat_name From feed_categories";
//		String getRssFeedsByCategory = "Select id From rss_feeds Where feed_cat_id=";
		String getFeedPagesQuery = "select page_id, page_name, page_xml from pages where page_id > 0";
		String getRssFeedsByPage = "select rc.rss_id from page_categories pc inner join rss_categories rc on pc.cat_id = rc.cat_id where pc.page_id=";
        

		try{
			MySql sql = new MySql();
			sql.connect();
			
			ResultSet pages = sql.executeSelect(getFeedPagesQuery);
			while(pages.next()){
//                System.out.println("\n\n------------------------- FRESH NEWS -----------------\n");
			
				WriteRSS ws = new WriteRSS();			
				ws.setLink("http://www.hirek.hu/rss.php?page="+pages.getString("page_xml").replace(".xml", ""));			
        		Calendar cal = new GregorianCalendar();	
				ws.setCopyright("Hirek.hu " + cal.get(Calendar.YEAR));
                String filename = pages.getString("page_xml").toLowerCase();
                
				
                ws.setRSSFileName(filename);

				ws.setTitle(pages.getString("page_name"));
				
			    //String query = 	"Select * From news2 Where rss_id in (Select id From rss_feeds Where feed_cat_id=" + categories.getInt("cat_id") + ") Order by news2.id DESC Limit 20";
			    String query = 	"Select * From news2 Where rss_id in (" + getRssFeedsByPage + pages.getInt("page_id") + ") Order by news2.id DESC Limit 20";
                
//                System.out.println(query+ "\n");

				ResultSet rs = sql.executeSelect(query);
				ArrayList items = new ArrayList();
				ArrayList itemsct = new ArrayList();
				
				
                while(rs.next()){		    	
				  	items.add(new RSSItems(rs.getString("news_title") + " - " + rs.getString("agency_name") , "http://www.hirek.hu/?from="+filename+"&amp;page_id=" + pages.getInt("page_id") + "&amp;news_id="+rs.getString("id"), rs.getString("news_lead").replaceAll("<", "&lt;").replaceAll(">", "&gt;")));
				  	itemsct.add(new RSSItems(rs.getString("news_title") + " - " + rs.getString("agency_name") , "http://www.hirek.hu/click.php?from="+filename+"&amp;page_id=" + pages.getInt("page_id") + "&amp;news_id="+rs.getString("id")+"&amp;rss="+rs.getInt("rss_id"), rs.getString("news_lead").replaceAll("<", "&lt;").replaceAll(">", "&gt;")));
				}
				
				ws.setRSSItems(items);
				ws.writeRSS();	

//                System.out.println("copy ws\n");

                WriteRSS wsct = ws;			
                wsct.ct = 1;
				wsct.setRSSItems(itemsct);
				wsct.writeRSS();	
                
//                System.out.println("rss finished ws\n");

			}
			sql.disconnect();
		}catch(Exception e){
			System.out.println(e);
		}
	}
}

class MyDate{
	private java.util.Date date = null;
	private Calendar cal = null;	
	
	public MyDate(){
		date = new java.util.Date();
		cal = new GregorianCalendar();	
	}
	
	public void setTop4Time(){
		cal.setTimeInMillis(date.getTime()-14400000);	
	}
	
	public void setTop12Time(){
		cal.setTimeInMillis(date.getTime()-43200000);	
	}
	
	public void setTop24Time(){
		cal.setTimeInMillis(date.getTime()-86400000);
	}
	
	public void setTop7x24Time(){ //top50
		cal.setTimeInMillis(date.getTime()-604800000);
	}
	
	public String getDate(){
		int hour24 = cal.get(Calendar.HOUR_OF_DAY);
		int min = cal.get(Calendar.MINUTE);
		int sec = cal.get(Calendar.SECOND);
		int year = cal.get(Calendar.YEAR);
		int month = cal.get(Calendar.MONTH);
		int day = cal.get(Calendar.DAY_OF_MONTH);
		return year+"-"+month+"-"+day+" "+hour24+":"+min+":"+sec;
	}
}

class WriteTemplates extends TimerTask{
	
	public WriteTemplates(){
	}		
	
	public void run(){
		try{
			MySql sql = new MySql();
			sql.connect();
			
			String templateDir = "/var/www/hirek.hu/www/www/templates/";
			String webDir = "/var/www/hirek.hu/www/www/";
				
			Configuration cfg = new Configuration();
			cfg.setDirectoryForTemplateLoading(new File(templateDir));
			cfg.setObjectWrapper(ObjectWrapper.BEANS_WRAPPER);
			
				
			ResultSet rs = sql.executeSelect("Select * From pages");
			String pageUpdated = "";
				
			while (rs.next()) {
				//optimized
				String pageCategories = "Select categories.cat_name as cat_name, cat_column, cat_position, categories.cat_id as cat_id, news_nr, cat_type, cat_html, cat_sql From page_categories Left Join categories On categories.cat_id=page_categories.cat_id Where page_id=" + rs.getInt("page_id") + " Order by cat_column, cat_position";
				ResultSet rs2 = sql.executeSelect(pageCategories);																				
					
				ArrayList categories = new ArrayList();										        												
				ArrayList firstCol = new ArrayList();
				ArrayList secondCol = new ArrayList();
				ArrayList thirdCol = new ArrayList();
					
				int i = 0;
				while(rs2.next()){												
					if(i!=rs2.getInt("cat_column")) i = rs2.getInt("cat_column");
					
					switch(rs2.getInt("cat_type")){
						case 1:// Normal RSS category
							//optimized
							String rssCategories = "Select rss_id From rss_categories Where cat_id=" + rs2.getInt("cat_id");
								
							ResultSet rs4 = sql.executeSelect(rssCategories);
								
							String rssIds = "";
							while(rs4.next()){						
								if(rs4.isLast())	rssIds = rssIds.concat(String.valueOf(rs4.getInt("rss_id")));
									else rssIds = rssIds.concat(String.valueOf(rs4.getInt("rss_id"))).concat(", ");												
							}
													
							if(rssIds!=""){
								//optimized but not using indexes
								//String news = "Select news_title, news_url From news Where rss_id in (" + rssIds + ") Order by dadd DESC Limit " + rs2.getInt("news_nr");
								String news = "Select news_title, id From news Where rss_id in (" + rssIds + ") Order by dadd DESC Limit " + rs2.getInt("news_nr");
								
								ResultSet rs3 = sql.executeSelect(news);					
								ArrayList rssList = new ArrayList();					
								while(rs3.next()){
									rssList.add(new RSSCategories(rs3.getString("news_title"), rs3.getInt("id")));
								}
										
								switch(i){
									case 0:firstCol.add(new PageCategories(rs2.getString("cat_name"), rs2.getInt("cat_column"), rs2.getInt("cat_position"), rssList));break;
									case 1:secondCol.add(new PageCategories(rs2.getString("cat_name"), rs2.getInt("cat_column"), rs2.getInt("cat_position"), rssList));break;
									case 2:thirdCol.add(new PageCategories(rs2.getString("cat_name"), rs2.getInt("cat_column"), rs2.getInt("cat_position"), rssList));break;
								}
							}
							break;
						case 2:// HTML content category
							
							break;
						case 3:// SQL content category
							ResultSet rs5 = sql.executeSelect(rs2.getString("cat_sql"));
							
							ArrayList rssList = new ArrayList();					
							
							while(rs5.next()){
								rssList.add(new RSSCategories(rs5.getString("news_title"), rs5.getInt("news_id")));
							}
										
							switch(i){
								case 0:firstCol.add(new PageCategories(rs2.getString("cat_name"), rs2.getInt("cat_column"), rs2.getInt("cat_position"), rssList));break;
								case 1:secondCol.add(new PageCategories(rs2.getString("cat_name"), rs2.getInt("cat_column"), rs2.getInt("cat_position"), rssList));break;
								case 2:thirdCol.add(new PageCategories(rs2.getString("cat_name"), rs2.getInt("cat_column"), rs2.getInt("cat_position"), rssList));break;
							}
							break;		
					}
					
					
					
				}
					
				categories.add(firstCol);
				categories.add(secondCol);
				categories.add(thirdCol);
				
			    Map root = new HashMap();			
				root.put("page_title", rs.getString("page_title"));
				root.put("page_keywords", rs.getString("page_keywords"));
				root.put("categories", categories);
						
				Template temp = cfg.getTemplate(rs.getString("page_template"));  
				//BufferedWriter out = new BufferedWriter(new FileWriter(webDir + rs.getString("page_html")));
				OutputStreamWriter out = new OutputStreamWriter(new FileOutputStream(webDir + rs.getString("page_html")), "UTF-8");
			    temp.process(root, out);
			        
			    out.close();
			    pageUpdated += rs.getString("page_name") + ", ";
			}
			sql.disconnect();
		}catch(Exception e){
			System.out.println("WriteTemplates error: " + e);
		}
	}
}

class GetNews extends TimerTask {
	private int feedType = 0;
	private String url = null;
	private int catId = 0;
	private int counter = 0;
	private int rssId = 0;
	private int agencyId = 0;
	private String agencyName = "";
	private String pattern = "";
	private String auxURL = "";
	private String matches = "";
	
	public GetNews(int feedType, int catId, String url, int rssId, int agencyId, String agencyName, String pattern, String auxURL, String matches){
		this.feedType = feedType;
		this.url = url;
		this.rssId = rssId;
		this.agencyId = agencyId;
		this.agencyName = agencyName;
		this.pattern = pattern;
		this.auxURL = auxURL;
		this.matches = matches;
		this.catId = catId;
	}
		
	private String getDate(){
		Calendar cal = new GregorianCalendar();		
		int hour24 = cal.get(Calendar.HOUR_OF_DAY);     // 0..23
		int min = cal.get(Calendar.MINUTE);             // 0..59
		int sec = cal.get(Calendar.SECOND);             // 0..59		
		    
		int year = cal.get(Calendar.YEAR);             // 2002
		int month = cal.get(Calendar.MONTH)+1;           // 0=Jan, 1=Feb, ...
		int day = cal.get(Calendar.DAY_OF_MONTH);      // 1...
		    	
    	return year+"-"+month+"-"+day+" "+hour24+":"+min+":"+sec;
	}
	
	public void run(){		
		String title = "";
		try{
			String statMessage = "";			
			MySql sql = new MySql();
		    sql.connect();
		    
		    if(this.feedType == 1){//RSS feed				
				DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();	    	        
			    factory.setCoalescing(true);
			    Document doc = factory.newDocumentBuilder().parse(new URL(this.url).openStream());
			    
			    RSS rss = new RSS(doc);
			    
			    title = rss.getRSSTitle();
			    this.counter++;
			    System.out.println(this.counter + ". " + rss.getRSSTitle());
			    
			    RSSItems rssItems[] = rss.getItems();
			    
			    
			    int nr = 0;
			    for(int i=0;i<rssItems.length;i++){
			    	//optimized
			    	/*String query = "Select count(*) as nr From news Where news_url='" + rssItems[i].getLink() + "' And rss_id=" + this.rssId + " Limit 1";
			    	ResultSet rs = sql.executeSelect(query);
			    	rs.next();		  		    	  	
			    	if(rs.getInt("nr")==0){			    		
			    		String insert = "Insert into news (rss_id, agency_id, agency_name, news_title, news_url, news_lead, dadd) Value (" + this.rssId + ", " +  this.agencyId + ", '" + this.agencyName + "', '" + rssItems[i].getTitle().replaceAll("[']", "&#039;") + "', '" + rssItems[i].getLink() + "', '" + rssItems[i].getDescription().replaceAll("[']", "&#039;") + "', '" + this.getDate() + "')";
			    		sql.executeInsert(insert);
			    		nr++;
			    	}*/
			    	String insert = "Insert into news2 (rss_id, cat_id, agency_id, agency_name, news_title, news_url, news_lead, dadd) Value (" + this.rssId + ", " + this.catId + " , " +  this.agencyId + ", '" + this.agencyName + "', '" + rssItems[i].getTitle().replaceAll("[']", "&#039;") + "', '" + rssItems[i].getLink() + "', '" + rssItems[i].getDescription().replaceAll("[']", "&#039;") + "', '" + this.getDate() + "')";
			    	sql.executeInsert(insert);
			    	
			    }
		  	}else if(this.feedType == 2){//html		    
			    System.out.println(this.counter + ". " + this.url);
			    
			    URL url = new URL(this.url);
				BufferedReader feedReader = new BufferedReader(new InputStreamReader(url.openStream(), "8859_2"));			
				
				CharSequence ch = null;
				String str;
				Pattern pattern = Pattern.compile(this.pattern);						
				String matches[] = this.matches.split(",");
				String newsURL = "";
				String newsTitle = "";
				String newsLead = "";
							
				int nr = 1;
				while ((str = feedReader.readLine()) != null) {								
					Matcher matcher = pattern.matcher(str);
				
					while(matcher.find()){		    				    											
						if(matches.length>=1){
							if(this.auxURL!=null || this.auxURL!="")	newsURL = this.auxURL + matcher.group(Integer.parseInt(matches[0]));
								else newsURL = matcher.group(Integer.parseInt(matches[0]));
						}	
						if(matches.length>=2) newsTitle = matcher.group(Integer.parseInt(matches[1]));
						if(matches.length==3) newsLead = matcher.group(Integer.parseInt(matches[2]));
						//optimized
						/*String query = "Select count(*) as nr From news Where news_url='" + newsURL + "' And rss_id=" + this.rssId + " Limit 1";
				    	ResultSet rs = sql.executeSelect(query);
				    	rs.next();		  		    	  	
				    	if(rs.getInt("nr")==0){				    		
				    		String insert = "Insert into news (rss_id, agency_id, agency_name, news_title, news_url, news_lead, dadd) Value (" + this.rssId + ", " +  this.agencyId + ", '" + this.agencyName + "', '" + newsTitle.replaceAll("[']", "&#039;") + "', '" + newsURL + "', '" + newsLead.replaceAll("[']", "&#039;") + "', '" + this.getDate() + "')";
				    		sql.executeInsert(insert);
				    		nr++;
				    	}*/
				    	String insert = "Insert into news2 (rss_id, cat_id, agency_id, agency_name, news_title, news_url, news_lead, dadd) Value (" + this.rssId + ", " + this.catId + ", " +  this.agencyId + ", '" + this.agencyName + "', '" + newsTitle.replaceAll("[']", "&#039;") + "', '" + newsURL + "', '" + newsLead.replaceAll("[']", "&#039;") + "', '" + this.getDate() + "')";
				    	sql.executeInsert(insert);						
					}
					//if(nr>=100)	break;	 
				}
				feedReader.close();			
			}
			
		    
		    
		    sql.disconnect();
		    
		    //BufferedWriter statOut = new BufferedWriter(new FileWriter("stat.txt", true));
		    //statOut.write(statMessage);
		    //statOut.close();
		    //new StatLogger("GetNews", statMessage);
		    
		    		    
	    } catch (Exception e) {
	    	System.out.println("GetNews - " + e);
	        //new ErrorLogger("GetNews", Level.SEVERE, "GetNews general error: " + e);
	    } 	        	
	}
}



public class Motor{
	public static void main(String[] args) {
	    try{
		    /*
		    Timer wt = new Timer("writeTemplates");		   		    	
		    WriteTemplates wtTask = new WriteTemplates();
		    wt.scheduleAtFixedRate(wtTask, 0, 1*60*1000);		    	        
			
			Timer wtn = new Timer("writeTopNews");		   		    	
		    WriteTopNews wtnTask = new WriteTopNews();
		    wtn.scheduleAtFixedRate(wtnTask, 0, 1*60*1000);		    	        
		    
		    */

			Timer wfn = new Timer("writeFreshNews");		   		    	
	        WriteFreshNews fnTask = new WriteFreshNews();
	        wfn.scheduleAtFixedRate(fnTask, 0, 1*60*1000);


			MySql sql = new MySql();
			sql.connect();
			
		    
			//optimized but not using indexes
			
			//ResultSet rs = sql.executeSelect("Select rss_name, feed_type, rss_url, id, agencies.agency_id, agency_name, pattern, aux_url, matches, period From rss_feeds Left Join agencies On agencies.agency_id=rss_feeds.agency_id Where status=1");
			
			//ResultSet rs = sql.executeSelect("Select rss_name, feed_type, rss_url, id, agencies.agency_id, agency_name, pattern, aux_url, matches, period From rss_feeds Left Join agencies On agencies.agency_id=rss_feeds.agency_id Where status=1 And feed_type=1");
			ResultSet rs = sql.executeSelect("Select rss_name, cat_id, feed_type, rss_url, id, agencies.agency_id, agency_name, pattern, aux_url, matches, period From feed_cats Left Join rss_feeds On rss_feeds.id=feed_cats.feed_id Left Join agencies On agencies.agency_id=rss_feeds.agency_id Where status=1 And feed_type=1");
			
			while (rs.next()) {	 
			    Timer t = new Timer(rs.getString("rss_name"));			    		   		    	
		    	GetNews gn = new GetNews(rs.getInt("feed_type"), rs.getInt("cat_id"), rs.getString("rss_url"), rs.getInt("id"), rs.getInt("agency_id"), rs.getString("agency_name"), rs.getString("pattern"), rs.getString("aux_url"), rs.getString("matches"));
		    	t.scheduleAtFixedRate(gn, 0, rs.getInt("period")*60*1000);		    	
			}
	        sql.disconnect();
	        
		
	    } catch (Exception e) {
	        System.out.println(e);
	    } 	
	    
    }                
}
