#!/bin/bash
set -ex
git checkout -b feat/add-ccmart-folder-final-6
rm -rf ccmart
mkdir ccmart
touch ccmart/.gitkeep
git status
git add ccmart
git status
git commit -m "feat: Add empty ccmart directory"
git status
