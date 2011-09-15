public class RSSCategories{
	private String newsTitle = null;
	private int newsID = 0;
	
	public RSSCategories(String newsTitle, int newsID){
		this.newsTitle = newsTitle;
		this.newsID = newsID;
	}
	
	public String getNewsTitle(){
		return this.newsTitle;
	}
	
	public String  getNewsID(){
		return String.valueOf(this.newsID).replace(",", "");
	}
	
}