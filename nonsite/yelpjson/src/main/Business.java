/* Business Object from Yelp */
package main;

import java.util.ArrayList;

public class Business {
	public String business_id = "";
	public String full_address  = "";
	public ArrayList<String> schools;
	public String open = "";
	public ArrayList<String> categories;
	public String photo_url = "";
	public String city = "";
	public int review_count;
	public String name = "";
	public ArrayList<String> neighborhoods;
	public String url = "";
	public double longitude;
	public String state = "";
	public double stars;
	public double latitude;
	public String type = "";
	
	public Business(String b, String a, String o, String p, String c, int r, String n, String u, double lon, String s, double st, double lat, String t) {
		if (b!=null)
			business_id = b;
		if (a!=null)
			full_address = a;
		if (o!=null)
			open = o;
		if (p!=null)
			photo_url = p;
		if (c!=null)
			city = c;
		review_count = r;
		if (n!=null)
			name = n;
		if (u!=null)
			url = u;
		longitude = lon;
		if (s!=null)
			state = s;
		stars = st;
		latitude = lat;
		if (t!=null)
			type = t;
	}
	
}
