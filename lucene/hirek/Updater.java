import MySql.*;
import java.sql.*;
import java.util.*;
import java.io.*;

import org.apache.lucene.index.IndexWriter;
import org.apache.lucene.analysis.standard.StandardAnalyzer;


class ArchiveMySql extends TimerTask {
	public ArchiveMySql(){
	}
	
	public void run(){
		
		try{
			MySql sql = new MySql();
			sql.connect();	
			
		
			Calendar cal = GregorianCalendar.getInstance();									
			String createQuery = "create table if not exists news_arch_" + cal.get(Calendar.YEAR) + "_" + (cal.get(Calendar.MONTH)+1) + " like news2"; 
			sql.executeSQL(createQuery);
			
			ResultSet rs = sql.executeSelect("Select id, rss_id, cat_id, cat_name, agency_id, agency_name, news_title, news_url, news_lead, dadd From news2 Where updated=1");
			
			while(rs.next()){								
				String updateQuery = "Update news2 Set updated=0 Where id=" + rs.getInt("id") + " Limit 1";			
				sql.executeSQL(updateQuery);
				
				String query = "Insert into news_arch_" + cal.get(Calendar.YEAR) + "_" + (cal.get(Calendar.MONTH)+1) + " Set rss_id=" + rs.getInt("rss_id") + ", cat_id=" + rs.getInt("cat_id") + ", cat_name='" + rs.getString("cat_name") + "', agency_id=" + rs.getInt("agency_id") + ", agency_name='" + rs.getString("agency_name") + "', news_title='" + rs.getString("news_title") + "', news_url='" + rs.getString("news_url") + "', news_lead='" + rs.getString("news_lead") + "', dadd='" + rs.getDate("dadd") + " " + rs.getTime("dadd") + "' ";	
				sql.executeInsert(query);								
			}
			
			sql.disconnect();
			System.out.println("MySql update succesfully finished!");
		}catch(Exception e){
			System.out.println(e);
		}
	}
}

class ArchiveLucene extends TimerTask {
	public ArchiveLucene(){
	}	
	
	public void run(){
		try{
			MySql sql = new MySql();
			sql.connect();	
		
			Calendar cal = GregorianCalendar.getInstance();									
			
			String createQuery = "create table if not exists news_arch_" + cal.get(Calendar.YEAR) + "_" + (cal.get(Calendar.MONTH)+1) + " like news2"; 
			sql.executeSQL(createQuery);

			ResultSet rs = sql.executeSelect("Select id, rss_id, cat_id, cat_name, agency_id, agency_name, news_title, news_url, news_lead, dadd From news_arch_" + cal.get(Calendar.YEAR) + "_" + (cal.get(Calendar.MONTH)+1) + " Where updated=1");
			
			File dataDir = new File("/var/www/hirek.hu/www/lucene/indexes/arch_"+ cal.get(Calendar.YEAR) + "_" + (cal.get(Calendar.MONTH)+1));
			
			IndexWriter indexWriter = null;
			
			if(dataDir.exists())	indexWriter = new IndexWriter(dataDir, new StandardAnalyzer(), false);
				else indexWriter = new IndexWriter(dataDir, new StandardAnalyzer(), true);
			
			indexWriter.mergeFactor = 100;
			indexWriter.maxMergeDocs = 9999999;
			indexWriter.minMergeDocs = 1000;
			
			/*
			Pattern pattern = Pattern.compile("(<.*?>)");
			Matcher matcher = null;
			*/
							        
			while(rs.next()){								
				
				String updateQuery = "Update news_arch_" + cal.get(Calendar.YEAR) + "_" + (cal.get(Calendar.MONTH)+1) + " Set updated=0 Where id=" + rs.getInt("id") + " Limit 1";
				sql.executeSQL(updateQuery);
				
				
				org.apache.lucene.document.Document ldoc = new org.apache.lucene.document.Document(); 	        									
				
				ldoc.add(new org.apache.lucene.document.Field("agency_id", String.valueOf(rs.getInt("agency_id")), org.apache.lucene.document.Field.Store.YES, org.apache.lucene.document.Field.Index.TOKENIZED));
				ldoc.add(new org.apache.lucene.document.Field("cat_id", String.valueOf(rs.getInt("cat_id")), org.apache.lucene.document.Field.Store.YES, org.apache.lucene.document.Field.Index.TOKENIZED));
				ldoc.add(new org.apache.lucene.document.Field("agency_name", rs.getString("agency_name"), org.apache.lucene.document.Field.Store.YES, org.apache.lucene.document.Field.Index.NO));
				ldoc.add(new org.apache.lucene.document.Field("news_title", rs.getString("news_title"), org.apache.lucene.document.Field.Store.YES, org.apache.lucene.document.Field.Index.TOKENIZED));
				ldoc.add(new org.apache.lucene.document.Field("news_url", rs.getString("news_url"), org.apache.lucene.document.Field.Store.YES, org.apache.lucene.document.Field.Index.TOKENIZED));
				ldoc.add(new org.apache.lucene.document.Field("news_lead", rs.getString("news_lead"), org.apache.lucene.document.Field.Store.YES, org.apache.lucene.document.Field.Index.TOKENIZED));
				ldoc.add(new org.apache.lucene.document.Field("dadd", rs.getDate("dadd") + " " + rs.getTime("dadd"), org.apache.lucene.document.Field.Store.YES, org.apache.lucene.document.Field.Index.TOKENIZED));
																
				indexWriter.addDocument(ldoc); 	
				
			}
			
			indexWriter.optimize();
	        indexWriter.close();
			
			sql.disconnect();
			
			System.out.println("Lucene update succesfully finished!");
		}catch(Exception e){
			System.out.println(e);
		}
	}
		
}


public class Updater{
	
	public static void main(String args[]){
		Timer t = new Timer("MySql Archive Updater");
	
		
		ArchiveMySql archiveMySql = new ArchiveMySql();
		t.scheduleAtFixedRate(archiveMySql, 0, 5*60*1000);		
		
		
		ArchiveLucene archiveLucene = new ArchiveLucene();
		t.scheduleAtFixedRate(archiveLucene, 0, 5*60*1000);		
	}
		
}
