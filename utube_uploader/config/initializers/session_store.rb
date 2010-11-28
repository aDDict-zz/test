# Be sure to restart your server when you modify this file.

# Your secret key for verifying cookie session data integrity.
# If you change this key, all old sessions will become invalid!
# Make sure the secret is at least 30 characters and all random, 
# no regular words or you'll be exposed to dictionary attacks.
ActionController::Base.session = {
  :key         => '_utube_uploader_session',
  :secret      => '4f465256e6a94c05cc69abecd9502960287b777e059e1d1075bfff7d26bff9001b0d178c4674a084b710487a694f18437d3332739efc0fdfac25f50214b20715'
}

# Use the database for sessions instead of the cookie-based default,
# which shouldn't be used to store highly confidential information
# (create the session table with "rake db:sessions:create")
# ActionController::Base.session_store = :active_record_store
