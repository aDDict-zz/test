#!/usr/local/rvm/rubies/ruby-1.8.7-p334/bin/ruby
require "rubygems"
require 'find'
require 'mysql'
require "ap"

spoolDir      = "/usr/local/maximas2/spool/"
spoolDirLog   = "/usr/local/maximas2/spool/logs"
wrongsprocDir = "/var/www/maxima/wrongsproc"

ids           = [
  68633,
  68239,
  68380,
  68382,
  68109,
  68110,
  68111,
  68114,
  68070,
  68476
]

$wrongMails   = []
@loggedMails  = []
@unsended     = []

def writeFile(file, content)
  File.open(file,"w") do |f|
    f.write(content)
  end
end

def getMails(file)
  IO.readlines(file).each do |line|
    $wrongMails.push("#{line.split(" ")[1]}\n")  if line.match(/\{email\}/)
  end
end

ids.each do |id|
  Find.find(wrongsprocDir) do |f|
    getMails(f) if f.match(/.#{id}.*/)
  end
end

def read(file)
  IO.readlines(file).each do |line|
    @loggedMails.push line
  end
end

ids.each do |id|
  Find.find(spoolDirLog) do |f|
    read(f) if f.match(/.#{id}.*/)
  end
end

content = ""
$wrongMails.each do |mail|
  if @loggedMails.include?(mail)
#     @unsended.push(mail)
     content += "#{mail}\n"
  end
end

writeFile("unsended.log",content)

#def rename(file)
#  newFile = "#{file.split(".")[0]}.spool"
#  File.rename(file, newFile)
#end

#Find.find(spoolDir) do |f|
#  rename(f) if f.match(/.68633.*\.sproc/)
#end
