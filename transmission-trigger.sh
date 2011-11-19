#!/bin/bash
downloads=/home/vvv/Downloads
movies=/home/vvv/Videos
log=$downloads/log.txt
TR_DOWNLOADED_PATH="$TR_TORRENT_DIR/$TR_TORRENT_NAME"
targetdir="$movies/$TR_TORRENT_NAME/"

mkdir "$targetdir";

find "TR_DOWNLOADED_PATH" -name \*.rar -exec unrar e {} \;
find -name \*.avi -exec mv {} "$targetdir" \;
find -name \*.iso -exec mv {} "$targetdir" \;
find -name \*.mkv -exec mv {} "$targetdir" \;

# find -name \*.rar -exec unrar e {} \;

#cd "$TR_DOWNLOADED_PATH"
#mkdir $targetdir
#echo $TR_DOWNLOADED_PATH >> $log
#echo $targetdir >> $log
#for i in $(find -name \*.rar | grep -v sample | grep -v Sample);
#do
#  unrar e $i;
#  echo $i >> $log
#done;
#for i in $(find -name \*.avi | grep -v sample | grep -v Sample);
#do
#  mv $i $targetdir;
#  echo $i >> $log
#done;
#for i in $(find -name \*.mkv | grep -v sample | grep -v Sample);
#do
#  mv $i $targetdir;
#  echo $i >> $log
#done;

#echo "-------------------------------" >> $log
#echo "===============================" >> $log
#echo "-------------------------------" >> $log
