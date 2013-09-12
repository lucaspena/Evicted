/* User Object from Yelp Dataset - we don't use it for our game though */
package main;

public class User {
	public Votes v;
	public String user_id;
	public String name;
	public String url;
	public double average_stars;
	public int review_count;
	public String type;

	public User(Votes v, String userID, String name, String url, double avgStars, int reviewCount, String type) {
		this.v = v;
		this.user_id = userID;
		this.url = url;
		this.average_stars = avgStars;
		this.review_count = reviewCount;
		this.type = type;
	}
}

