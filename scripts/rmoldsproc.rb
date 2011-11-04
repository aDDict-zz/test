#!/usr/local/rvm/rubies/ruby-1.8.7-p334/bin/ruby
require "rubygems"
require 'mysql'
require 'find'
require "ap"
require "IO.rb"
require 'fileutils'

exit if ARGV[0] == nil

spoolDir      = "/usr/local/maximas2/spool"
spoolDirLog   = "/usr/local/maximas2/spool/logs"
wrongsprocDir = spoolDir # "/var/www/maxima/wrongsproc"
emailMatcher  = "^[A-Za-z0-9_%+-]+[A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$"
spoolsArr     = []
@loggedMails  = []
@filename     = ""
@unsended     = []
@wrongMails   = []

## deleting the old ones
Find.find(wrongsprocDir) do |f|
#  File.delete(f) if f.match(/.#{ARGV[0]}.*/)

#  File.delete(f) if f.match(/.#{ARGV[0]}.*\.sp/)
  puts f if f.match(/.#{ARGV[0]}.*\.sp/)

end

puts "DONE!"
