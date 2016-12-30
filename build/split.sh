#!/usr/bin/env bash
git subsplit init git@github.com:zenify/zenify.git

git subsplit publish --heads="master" --no-tags packages/CodingStandard:git@github.com:Zenify/CodingStandard.git
git subsplit publish --heads="master" --no-tags packages/DoctrineBehaviors:git@github.com:Zenify/DoctrineBehaviors.git
git subsplit publish --heads="master" --no-tags packages/DoctrineExtensionsTree:git@github.com:Zenify/DoctrineExtensionsTree.git
git subsplit publish --heads="master" --no-tags packages/DoctrineFilters:git@github.com:Zenify/DoctrineFilters.git
git subsplit publish --heads="master" --no-tags packages/DoctrineFixtures:git@github.com:Zenify/DoctrineFixtures.git
git subsplit publish --heads="master" --no-tags packages/DoctrineMigrations:git@github.com:Zenify/DoctrineMigrations.git
git subsplit publish --heads="master" --no-tags packages/ModularLatteFilters:git@github.com:Zenify/ModularLatteFilters.git
git subsplit publish --heads="master" --no-tags packages/NetteDatabaseFilters:git@github.com:Zenify/NetteDatabaseFilters.git

rm -rf .subsplit/

# inspired by laravel: https://github.com/laravel/framework/blob/master/build/illuminate-split.sh
