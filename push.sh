#!/bin/sh
git add .
read commitMessage
git commit -am "$commitMessage"
git push
# update tags
git push --delete origin 1.0.0
git tag --delete 1.0.0
git tag 1.0.0
git push --tags
echo Press Enter...
read