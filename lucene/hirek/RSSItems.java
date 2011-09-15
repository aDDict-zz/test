import javax.xml.parsers.*;
import org.xml.sax.*;
import org.xml.sax.helpers.*;
import org.w3c.dom.*;

import java.io.*;

public class RSSItems{
	private String title = "";
	private String link = "";
	private String lead = "";
	private String pubDate = "";
	private String category = "";
	private String description = "";
	private String content = "";	
	private Node itemNode = null;	
	
	public RSSItems(){				
	}
	
	public RSSItems(String title, String link){		
		this.title = title;
		this.link = link;
	}
	
	public RSSItems(String title, String link, String lead){		
		this.title = title;
		this.link = link;
		this.lead = lead;
	}
	
	public RSSItems(Node itemNode){
		this.itemNode = itemNode;
	}
				
	public RSSItems getItemsChildNodes() throws UnsupportedEncodingException{
	    for(int j=0;j<this.itemNode.getChildNodes().getLength();j++){	    	
	    	if(this.itemNode.getChildNodes().item(j)!=null){
		    	if(this.itemNode.getChildNodes().item(j).getNodeName().equals("title")) this.title = this.itemNode.getChildNodes().item(j).getTextContent();
		    	if(this.itemNode.getChildNodes().item(j).getNodeName().equals("link")) this.link = this.itemNode.getChildNodes().item(j).getTextContent();
		    	if(this.itemNode.getChildNodes().item(j).getNodeName().equals("pubDate")) this.pubDate = this.itemNode.getChildNodes().item(j).getTextContent();
		    	if(this.itemNode.getChildNodes().item(j).getNodeName().equals("category")) this.category = this.itemNode.getChildNodes().item(j).getTextContent();
		    	if(this.itemNode.getChildNodes().item(j).getNodeName().equals("description")) this.description = this.itemNode.getChildNodes().item(j).getTextContent();
		    	if(this.itemNode.getChildNodes().item(j).getNodeName().equals("content:encoded")) this.content = this.itemNode.getChildNodes().item(j).getTextContent();		    	
	    	}
	    }	    	
	    return this;	    
	}
		
	
	public String getTitle(){
		return this.title;
	}
	
	public String getLink(){
		return this.link;
	}
	
	public String getLead(){
		return this.lead;
	}
	
	public String getPubDate(){
		return this.pubDate;
	}
	
	public String getCategory(){
		return this.category;
	}
	
	public String getDescription(){
		return this.description;
	}
	
	public String getContent(){
		return this.content;
	}
}