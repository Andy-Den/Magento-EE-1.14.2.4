#!/usr/bin/env bash

# utility
#########

display_help() {
  cat <<-EOF

  A utility to open an environment in tmux 

  Usage: tmux.sh [environment] 
  
  Arguments:
    environment          Environment to tmux into

    
  Options:
    --help               Display help

EOF
  
  if [ $# -eq 0 ]; then
    exit 0
  fi
  
  exit $1
}

# globals
#########

type greadlink >/dev/null 2>&1 && CWD="$(dirname "$(greadlink -f "$0")")" || \
  CWD="$(dirname "$(readlink -f "$0")")"
REPO_ROOT=`git rev-parse --show-toplevel`


# run functions
###############

. $CWD/.functions.sh || error "unable to load shared functions"


env_tmux(){

  env_bootstrap
  
  cd $ENV_DIR
  tmuxp load tmuxp.yml
}


# runtime
#########

runstr="env_tmux"

while [ $# -ne 0 ]; do
  case $1 in
    -h|--help|help)     display_help ;;
    *)                  if [[ $1 == -* ]]; then
                          echo "invalid option: $1" ; display_help 1
                        fi
                        ENV="$1"
                        ;;    
  esac
  shift
done
  
$runstr
exit $?
