#!/usr/bin/env bash
git subsplit init git@github.com:zenify/zenify.git

git subsplit publish --heads="master" packages/ModularLatteFilters:git@github.com:Symplify/ModularLatteFilters.git

rm -rf .subsplit/

# inspired by laravel: https://github.com/laravel/framework/blob/master/build/illuminate-split-full.sh
