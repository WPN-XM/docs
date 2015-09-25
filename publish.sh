#!/bin/sh

setup_git() {
  git config --global user.email "travis@travis-ci.org"
  git config --global user.name "Travis CI"
}

commit_website_files() {
  git fetch origin
  git checkout -b gh-pages
  git add . *.html
  git commit --message "Travis build: $TRAVIS_BUILD_NUMBER -- [skip ci]"
}

upload_files() {
  git remote add origin-pages https://${GH_TOKEN}@github.com/WPN-XM/docs > /dev/null 2>&1
  git push -f --set-upstream origin-pages gh-pages
}

setup_git
commit_website_files
upload_files