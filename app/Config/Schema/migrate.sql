# Migration from old system to new.... dang ssh dies out on me. =(

# DONT FORGET COPYING webroot/uploads (site_id)

# SITE
#
INSERT INTO portal.rescues (id,hostname,title,user_id,created,modified,disabled,plan,domain,layout,theme) 
(SELECT id,hostname,title,user_id,created,modified,disabled,plan,domain,layout,theme FROM hp.sites);


# CONTACT
#
UPDATE portal.rescues r LEFT JOIN hp.contact_pages c ON c.site_id = r.id
	SET r.phone=c.phone,r.email=c.email,r.address=c.address,r.city=c.city,r.state=c.state,r.zip_code=c.zip_code;

UPDATE portal.rescues r LEFT JOIN hp.paypal_credentials p ON r.id = p.site_id
	SET r.paypal_email = p.account_email;


# ABOUT
#
UPDATE portal.rescues r LEFT JOIN hp.about_pages a ON a.site_id = r.id
	SET r.about=IFNULL(a.mission,a.overview),r.history=a.history,r.about_photo_id=a.page_photo_id;


# HOMEPAGE
#
UPDATE portal.rescues r LEFT JOIN hp.homepages h ON h.site_id = r.id
	SET r.page_photo_id=h.page_photo_id,r.facebook_url=h.facebook_like_url,r.sidebar_title=h.sidebar_title,r.sidebar_content=h.sidebar_content ;


# DESIGN
#
UPDATE portal.rescues r LEFT JOIN hp.site_designs s ON s.site_id = r.id
	SET r.theme = s.theme, r.color1=s.color1,r.color2=s.color2,r.facebook_url=s.facebook_url,r.twitter_url=s.twitter_url;

#
INSERT INTO users (id,rescue_id,email,first_name,last_name,username,password,admin,manager,created,modified,invite,invited,page_photo_id,last_login,login_count)
     	(SELECT id,site_id,email,first_name,last_name,username,password,admin,manager,created,modified,invite,invited,page_photo_id,last_login,login_count
	FROM hp.users h_users);


########## DONE ABOVE #########################

###################################################################
# HARD STUFF...

#
INSERT INTO adoptables (id,rescue_id,adopter_id,name,adoptable_photo_id,biography,species,breed,mixed_breed,breed2,birthdate,gender,weight_lbs,adult_size,child_friendly,minimum_child_age,cat_friendly,dog_friendly,neutered_spayed,fostered,date_fosterable,date_available,adoption_cost,status,date_adopted,created,modified,energy_level,special_needs,microchip,enable_sponsorship,sponsorship_goal,sponsorship_goal_recurring,sponsorship_details)
	(SELECT id,site_id,adoptable_owner_id,name,page_photo_id,biography,species,breed,mixed_breed,breed2,birthdate,gender,weight_lbs,adult_size,child_friendly,minimum_child_age,cat_friendly,dog_friendly,neutered_spayed,fostered,date_fosterable,date_available,adoption_cost,status,date_adopted,created,modified,energy_level,special_needs,microchip,enable_sponsorship,sponsorship_goal,sponsorship_goal_recurring,sponsorship_details 
	FROM hp.rescue_adoptables h_adoptables);

# Some go into adoptable_photos (main photo)
#
INSERT INTO adoptable_photos (id,rescue_id,adoptable_id,title,path,caption,filename,ext,type,size,ix,created,modified)
 	(SELECT p.id,adoptables.rescue_id,adoptables.id,p.title,p.path,p.caption,p.filename,p.ext,p.type,p.size,p.ix,p.created,p.modified
	FROM hp.page_photos p JOIN adoptables ON p.id = adoptables.adoptable_photo_id);

# Some go into success_story_photos
#
INSERT INTO success_story_photos (id,rescue_id,title,path,caption,filename,ext,type,size,ix,created,modified)
 	(SELECT p.id,success_stories.site_id,p.title,p.path,p.caption,p.filename,p.ext,p.type,p.size,p.ix,p.created,p.modified
	FROM hp.page_photos p JOIN hp.rescue_adoption_stories success_stories ON p.id = success_stories.page_photo_id);


# List photos for adoptable
#
INSERT INTO adoptable_photos (rescue_id,adoptable_id,ix,photo_url,title,path,filename,ext,type,size,caption,created,modified)
(SELECT site_id,adoptable_id,ix,photo_url,title,path,filename,ext,type,size,caption,created,modified
	FROM hp.rescue_adoptable_photos h_adoptable_photos);


#
INSERT INTO adopters (id,rescue_id,adoptable_id,email,first_name,last_name,home_phone,cell_phone,work_phone,best_time_to_call,address,address_2,city,state,zip_code,pet_ownership_history,home_details,care_and_responsibility,preference,adopters.references,referral_source,data,status,created,modified)
 	(SELECT id,site_id,adoptable_id,email,first_name,last_name,home_phone,cell_phone,work_phone,best_time_to_call,address,address_2,city,state,zip_code,pet_ownership_history,home_details,care_and_responsibility,preference,ra.references,referral_source,data,status,created,modified
	FROM hp.rescue_adoptions ra);

#
UPDATE adopters SET status = 'Approved' WHERE status = 'Accepted';


# Split fosters into two/three tables.

# Foster profiles
INSERT INTO fosters (id,email,first_name,last_name,username,password,page_photo_id,invite,home_phone,cell_phone,work_phone,best_time_to_call,address,address_2,city,state,zip_code,referral_source,data,home_details,status,created,modified,disabled)
  	(SELECT id,email,first_name,last_name,username,password,page_photo_id,invite,home_phone,cell_phone,work_phone,best_time_to_call,address,address_2,city,state,zip_code,referral_source,data,home_details,status,created,modified,disabled
	FROM hp.rescue_fosters h_fosters);

# Foster users
INSERT INTO users (email,first_name,last_name,password,page_photo_id,invite)
	(SELECT email,first_name,last_name,password,page_photo_id,invite
	FROM fosters WHERE fosters.password != '' OR fosters.invite != '');

# Fix foster profiles.
UPDATE fosters (user_id) 
	(SELECT id FROM users WHERE users.email = fosters.email);

# Foster applicants
INSERT INTO rescue_fosters (id,foster_id,user_id,rescue_id,status,created,modified)
	(SELECT id,id,user_id,rescue_id,status,created,modified
	FROM fosters);

#################################################

# Split volunteers into two/three tables.
,

# Volunteer profiles...
INSERT INTO volunteers (id,email,first_name,last_name,home_phone,cell_phone,work_phone,best_time_to_call,address,address_2,city,state,zip_code,referral_source,data,home_details,created,modified,disabled)
	(SELECT id,email,first_name,last_name,home_phone,cell_phone,work_phone,best_time_to_call,address,address_2,city,state,zip_code,referral_source,data,home_details,created,modified,disabled 
	FROM hp.rescue_volunteers h_volunteers);


# Volunteer users
INSERT INTO users (email,first_name,last_name,password,page_photo_id,invite)
	(SELECT email,first_name,last_name,password,page_photo_id,invite
	FROM hp.rescue_volunteers WHERE hp.rescue_volunteers.password != '' OR hp.rescue_volunteers.invite != '');

# Fix volunteer profiles.
UPDATE volunteers LEFT JOIN users ON users.email = volunteers.email
	SET user_id = users.id;

# Volunteer applicants
# Needs to include proper volunteer_id
INSERT INTO rescue_volunteers (id,volunteer_id,rescue_id,interests,availability,experience,status,created,modified)
 	(SELECT id,id,site_id,interests,availability,experience,status,created,modified 
	FROM hp.rescue_volunteers h_rescue_volunteers);

# Volunteer applicants also need user_id set from new user...
UPDATE rescue_volunteers LEFT JOIN volunteers ON volunteer_id = volunteers.id
	SET rescue_volunteers.user_id = volunteers.user_id;


# Split success stories into adoptables and success_story_photos
UPDATE adoptables LEFT JOIN hp.rescue_adoption_stories s 
	ON s.site_id = adoptables.rescue_id AND s.adoptable_id = adoptables.id
	SET adoptables.success_story = s.content, adoptables.story_date = s.created,
	adoptables.success_story_photo_id = s.page_photo_id;


#######################################

# DONE BELOW....

#
INSERT INTO about_page_bios (id,name,title,page_photo_id,description,ix,created,modified,rescue_id)
	(SELECT id,name,title,page_photo_id,description,ix,created,modified,site_id
	FROM hp.about_page_bios h_about_page_bios);

#
INSERT INTO adoption_downloads (rescue_id,user_id,ix,title,name,filename,path,size,ext,type,description,created,modified)
       	(SELECT site_id,user_id,ix,title,name,filename,path,size,ext,type,description,created,modified
	FROM hp.rescue_adoption_downloads h_adoption_downloads);

#
INSERT INTO adoption_faqs (rescue_id,ix,question,answer,created,modified)
   	(SELECT site_id,ix,question,answer,created,modified
	FROM hp.rescue_adoption_faqs h_adoption_faqs);

#
INSERT INTO adoption_forms (rescue_id,title,introduction,acknowledgment,species,custom_title,custom_fields,disabled,created,modified)
  	(SELECT site_id,title,introduction,acknowledgment,species,custom_title,custom_fields,disabled,created,modified
	FROM hp.rescue_adoption_forms h_adoption_forms);

#
INSERT INTO adoption_pages (rescue_id,title,url,page_photo_id,content,created,modified)
       	(SELECT site_id,title,url,page_photo_id,content,created,modified
	FROM hp.rescue_adoption_pages h_adoption_pages);

#
INSERT INTO adoption_page_indices (rescue_id,title,introduction,disabled,created,modified,page_photo_id)
       	(SELECT site_id,title,introduction,disabled,created,modified,page_photo_id
	FROM hp.rescue_adoption_overviews h_adoption_page_indices);

#
INSERT INTO contacts (name,title,phone,alternate_phone,email,details,ix,created,modified,rescue_id)
    	(SELECT name,title,phone,alternate_phone,email,details,ix,created,modified,site_id
	FROM hp.contacts h_contacts);

#
INSERT INTO donation_pages (rescue_id,title,page_photo_id,introduction,wishlist,created,modified)
    	(SELECT site_id,title,page_photo_id,introduction,wishlist,created,modified
	FROM hp.donation_pages h_donation_pages);

INSERT INTO events (rescue_id,user_id,title,event_location_id,event_contact_id,start_date,end_date,start_time,end_time,summary,details,created,modified,page_photo_id,url,dates,project_id,members_only)
       	(SELECT site_id,user_id,title,event_location_id,event_contact_id,start_date,end_date,start_time,end_time,summary,details,created,modified,page_photo_id,url,dates,project_id,members_only
	FROM hp.events h_events);

#
INSERT INTO event_contacts (id,rescue_id,user_id,name,phone,email,comments,created,modified)
 	(SELECT id,site_id,user_id,name,phone,email,comments,created,modified
	FROM hp.event_contacts h_event_contacts);

#
INSERT INTO event_locations (id,rescue_id,user_id,name,address,address_2,city,state,zip_code,country,phone,comments,created,modified)
       	(SELECT id,site_id,user_id,name,address,address_2,city,state,zip_code,country,phone,comments,created,modified
	FROM hp.event_locations h_event_locations);

#
INSERT INTO foster_downloads (rescue_id,user_id,ix,title,name,filename,path,size,ext,type,description,created,modified)
(SELECT site_id,user_id,ix,title,name,filename,path,size,ext,type,description,created,modified
	FROM hp.rescue_foster_downloads h_foster_downloads);

#
INSERT INTO foster_faqs (rescue_id,ix,question,answer,created,modified)
     	(SELECT site_id,ix,question,answer,created,modified
	FROM hp.rescue_foster_faqs h_foster_faqs);

#
INSERT INTO foster_forms (rescue_id,title,introduction,acknowledgment,disabled,created,modified)
    	(SELECT site_id,title,introduction,acknowledgment,disabled,created,modified
	FROM hp.rescue_foster_forms h_foster_forms);

#
INSERT INTO foster_pages (rescue_id,title,url,page_photo_id,content,created,modified)
       	(SELECT site_id,title,url,page_photo_id,content,created,modified
	FROM hp.rescue_foster_pages h_foster_pages);

#
INSERT INTO foster_page_indices (rescue_id,title,page_photo_id,introduction,disabled,created,modified)
     	(SELECT site_id,title,page_photo_id,introduction,disabled,created,modified
	FROM hp.rescue_foster_overviews h_foster_page_indices);

#
INSERT INTO news_posts (rescue_id,user_id,draft_id,published,title,summary,content,created,modified,page_photo_id,url,project_id,members_only)
	(SELECT site_id,user_id,draft_id,published,title,summary,content,created,modified,page_photo_id,url,project_id,members_only
	FROM hp.news_posts h_news_posts);

# XXX everything that uses page_photo_id needs to be modified...
INSERT INTO page_photos (id,user_id,title,path,thumb_path,caption,filename,ext,type,size,ix,created,modified,crop_x,crop_y,crop_h,crop_w)
 	(SELECT id,user_id,title,path,thumb_path,caption,filename,ext,type,size,ix,created,modified,crop_x,crop_y,crop_h,crop_w
	FROM hp.page_photos h_page_photos); 


#
INSERT INTO photos (rescue_id,user_id,photo_album_id,ix,photo_url,title,path,filename,ext,type,size,caption,created,modified,rotate)
      	(SELECT site_id,user_id,photo_album_id,ix,photo_url,title,path,filename,ext,type,size,caption,created,modified,rotate
	FROM hp.photos h_photos);

#
INSERT INTO photo_albums (id,rescue_id,user_id,title,description,created,modified,sticky,url,project_id,members_only)
 	(SELECT id,site_id,user_id,title,description,created,modified,sticky,url,project_id,members_only
	FROM hp.photo_albums h_photo_albums);

#
INSERT INTO rescue_logos (rescue_id,user_id,title,path,thumb_path,caption,filename,ext,type,size,ix,crop_x,crop_y,crop_w,crop_h,created,modified)
 	(SELECT site_id,user_id,title,path,thumb_path,caption,filename,ext,type,size,ix,crop_x,crop_y,crop_w,crop_h,created,modified
	FROM hp.site_design_logos h_rescue_logos);

#
UPDATE rescues LEFT JOIN rescue_logos ON rescues.id = rescue_logos.rescue_id SET rescues.rescue_logo_id = rescue_logos.id;


#
INSERT INTO resources (rescue_id,user_id,resource_category_id,url,title,description,address,phone,ix,created,modified)
    	(SELECT site_id,user_id,resource_category_id,url,title,description,address,phone,ix,created,modified
	FROM hp.resources h_resources);

#
INSERT INTO resource_categories (id,rescue_id,user_id,title,ix,created,modified)
    	(SELECT id,site_id,user_id,title,ix,created,modified
	FROM hp.resource_categories h_resource_categories);

#
INSERT INTO resource_pages (rescue_id,title,introduction,created,modified)
	(SELECT site_id,title,introduction,created,modified
	FROM hp.resource_pages h_resource_pages);

#
INSERT INTO volunteer_downloads (rescue_id,user_id,ix,title,name,filename,path,size,ext,type,description,created,modified)
     	(SELECT site_id,user_id,ix,title,name,filename,path,size,ext,type,description,created,modified
	FROM hp.rescue_volunteer_downloads h_volunteer_downloads);

#
INSERT INTO volunteer_faqs (rescue_id,ix,question,answer,created,modified)
      	(SELECT site_id,ix,question,answer,created,modified
	FROM hp.rescue_volunteer_faqs h_volunteer_faqs);

#
INSERT INTO volunteer_forms (rescue_id,title,introduction,acknowledgment,disabled,created,modified)
     	(SELECT site_id,title,introduction,acknowledgment,disabled,created,modified
	FROM hp.rescue_volunteer_forms h_volunteer_forms);

#
INSERT INTO volunteer_pages (rescue_id,title,url,page_photo_id,content,created,modified)
	(SELECT site_id,title,url,page_photo_id,content,created,modified
	FROM hp.rescue_volunteer_pages h_volunteer_pages);

#
INSERT INTO volunteer_page_indices (rescue_id,title,page_photo_id,introduction,disabled,created,modified)
      	(SELECT site_id,title,page_photo_id,introduction,disabled,created,modified
	FROM hp.rescue_volunteer_overviews h_volunteer_page_indices);


##################################
UPDATE portal.rescue_volunteers LEFT JOIN portal.volunteers ON rescue_volunteers.volunteer_id = volunteers.id SET status = 'Active Offline' WHERE status = 'Active' AND disabled = 1;
