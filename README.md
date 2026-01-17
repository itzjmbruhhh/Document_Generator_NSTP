# PART 1: Git setup and cloning a repository

## 1. Install Git on Windows

- Go to https://git-scm.com

- Download the Windows installer.

- Run it and keep the defaults.
- The important part is: “Git from the command line and also from 3rd-party software” should be selected.

After installation, open a new terminal and verify:


```bash
git --version
```

If you see a version number, you’re good.

## 2. Configure your Git username and email

This ties your commits to your GitHub account.


```bash
git config --global user.name "Your Name"
git config --global user.email "your_email@example.com"
```

Check that it worked:


```bash
git config --global --list
```

## 3. Clone the repository

Choose or create a folder where you want the project.

Example:


```bash
cd Documents
```

Clone the repo:


```bash
git clone https://github.com/itzjmbruhhh/Document_Generator_NSTP.git
```

Move into the project:


```bash
cd Document_Generator_NSTP
```

Check status:


```bash
git status
```

At this point, you’re on the default branch (usually main).

# PART 2: Branching, switching, pushing, pulling

## 4. Create a new branch

Never work directly on main.


```bash
git branch {NAME-OF-YOUR-BRANCH}
```

Switch to it:


```bash
git checkout {NAME-OF-YOUR-BRANCH}
```

Shortcut (create + switch):


```bash
git checkout -b {NAME-OF-YOUR-BRANCH}
```

Confirm:


```bash
git branch
```

The active branch has an asterisk \*.

## 5. Make changes and commit

After editing files:

Check what changed:


```bash
git status
```

Stage changes:


```bash
git add .
```

Commit:


```bash
git commit -m "Add initial feature implementation"
```

## 6. Push your branch to GitHub

The first push links your local branch to GitHub.


```bash
git push -u origin feature-my-work
```

After this, future pushes are just:


```bash
git push
```

## 7. Pull latest changes from main

Before continuing work, always sync.

Switch to main:


```bash
git checkout main
```

Pull:


```bash
git pull origin main
```

Update your branch with latest main:


```bash
git checkout feature-my-work
git merge main
```

(If there are conflicts, Git will tell you exactly which files to fix.)

# PART 3: Creating Pull Requests (PRs)

## 8. Push final changes

Make sure everything is committed and pushed:


```bash
git status
git push
```

## 9. Create the Pull Request on GitHub

1. Go to the repository on GitHub.

2. You’ll see a banner saying your branch was recently pushed.

3. Click “Compare & pull request”.

If not:

1. Click Pull requests

2. Click New pull request

3. Base: main

4. Compare: {YOUR BRANCH}

## 10. Fill out the PR

- Title: short and clear
  Example: Add document generation logic

- Description:

1. What you changed

2. Why you changed it

3. Anything reviewers should check

Click Create pull request.

## 11. After approval

Once approved and merged:


```bash
git checkout main
git pull origin main
```

Quick mental model

1. Clone once

2. Create a branch per feature

3. Commit often

4. Push your branch

5. Open PR

6. Merge

7. Pull main
