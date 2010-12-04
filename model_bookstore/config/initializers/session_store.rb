# Be sure to restart your server when you modify this file.

# Your secret key for verifying cookie session data integrity.
# If you change this key, all old sessions will become invalid!
# Make sure the secret is at least 30 characters and all random, 
# no regular words or you'll be exposed to dictionary attacks.
ActionController::Base.session = {
  :key         => '_model_bookstore_session',
  :secret      => '28a1276f33b1ffccad0641d782978d457bb52a6c60e7a7022bd55d65584c48e6d51b6166eabf8e046bd4003c5ccd7427f6b58f0ff14811a5f228c0261ecc47cc'
}

# Use the database for sessions instead of the cookie-based default,
# which shouldn't be used to store highly confidential information
# (create the session table with "rake db:sessions:create")
# ActionController::Base.session_store = :active_record_store
