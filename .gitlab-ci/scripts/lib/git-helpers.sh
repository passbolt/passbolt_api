function setup_gpg_key() {
  key_path="$1"
  passphrase="$2"
  grip="$3"

  mkdir -p ~/.gnupg
  echo "allow-preset-passphrase" > ~/.gnupg/gpg-agent.conf
  gpg-agent --homedir ~/.gnupg --use-standard-socket --daemon
  /usr/lib/gnupg2/gpg-preset-passphrase -c "$grip" <<< "$passphrase"
  gpg --pinentry-mode loopback --passphrase "$passphrase" --import "$key_path"
}

function setup_git_user() {
  local email="$1"
  local name="$2"
  git config --global user.email "$email"
  git config --global user.name "$name"
  git config --global commit.gpgsign true
  git config --global user.signingkey "$email"
}

function create_git_tag() {
  local passbolt_version="$1"
  local passbolt_flavour="$2"
  local filter="$3"
  local component="$4"
  local tag="$passbolt_version-$passbolt_flavour-$filter"
  git tag -a "$tag" -m "Release $tag version for $component package" > /dev/null
}
