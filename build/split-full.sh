#!/usr/bin/env bash
git subsplit init git@github.com:zenify/zenify.git

git subsplit publish --heads="master" packages/CodingStandard:git@github.com:Zenify/CodingStandard.git
git subsplit publish --heads="master" packages/DoctrineBehaviors:git@github.com:Zenify/DoctrineBehaviors.git
git subsplit publish --heads="master" packages/DoctrineExtensionsTree:git@github.com:Zenify/DoctrineExtensionsTree.git
git subsplit publish --heads="master" packages/DoctrineFilters:git@github.com:Zenify/DoctrineFilters.git
git subsplit publish --heads="master" packages/DoctrineFixtures:git@github.com:Zenify/DoctrineFixtures.git
git subsplit publish --heads="master" packages/DoctrineMigrations:git@github.com:Zenify/DoctrineMigrations.git
git subsplit publish --heads="master" packages/ModularLatteFilters:git@github.com:Zenify/ModularLatteFilters.git
git subsplit publish --heads="master" packages/NetteDatabaseFilters:git@github.com:Zenify/NetteDatabaseFilters.git

rm -rf .subsplit/

# inspired by laravel: https://github.com/laravel/framework/blob/master/build/illuminate-split-full.sh
