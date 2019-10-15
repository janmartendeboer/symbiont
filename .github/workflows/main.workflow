workflow "Build Composer package" {
  resolves = ["First interaction"]
  on = "push"
}

action "First interaction" {
  uses = "actions/first-interaction@215051d77c3ebf704bb3f11dc853ccde76c985ef"
}
