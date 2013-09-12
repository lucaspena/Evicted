/* Review Object from Yelp */

package main;

public class Review {
	public Votes votes;
	public String user_id;
	public String review_id;
	public int stars;
	public String date;
	public String text;
	public String type;
	public String business_id;
	
	public Review(Votes v, String u, String r, int s, String d, String te, String ty, String b) {
		votes = v;
		user_id = u;
		review_id = r;
		stars = s;
		date = d;
		text = te;
		type = ty;
		business_id = b;
	}
}
