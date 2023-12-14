# Code contibution to this app

## 🚀 Project Archival Announcement

Dear ProBIND Community,

After careful consideration, I've decided to archive this project due to time constraints and the challenges of maintaining it on my own. I want to express my gratitude to everyone who has contributed and supported ProBIND over the years.

### ❗️ Why Archive?

Maintaining ProBIND has been a rewarding journey, but the demands of my current commitments prevent me from giving it the attention it deserves.

### 📢 Call for Contributors

I believe in the potential of this community to carry the project forward. If you've benefited from ProBIND or have a passion for it, I invite you to consider contributing. While new issues and pull requests cannot be submitted directly to this repository, you can fork the project, make changes, and share your work.

### 🙏 Thank You

Thank you for your support and contributions in the past.

Best regards, Paco Orozco

---

Contributions are **welcomed** and will be fully **credited** (see [AUTHORS](AUTHORS)).

We accept contributions via Pull Requests on [GitHub Repository][github].

## <a name="issue"></a> Found an Issue?
If you find a bug in the source code or a mistake in the documentation, you can help us by submitting an issue to our [GitHub Repository][github]. Even better you can submit a Pull Request with a fix.

**Please see the Submission Guidelines below**.

## <a name="feature"></a> Want a Feature?
You can request a new feature by submitting an issue to our [GitHub Repository][github].  If you would like to implement a new feature then consider what kind of change it is:

* **Major Changes** that you wish to contribute to the project should be discussed first, please email to me so that we can better coordinate our efforts, prevent duplication of work, and help you to craft the change so that it is successfully accepted into the project.
* **Small Changes** can be crafted and submitted to the [GitHub Repository][github] as a Pull Request.

## <a name="submit"></a> Submission Guidelines

### Submitting an Issue
Before you submit your issue search the archive, maybe your question was already answered.

If your issue appears to be a bug, and hasn't been reported, open a new issue.
Help us to maximize the effort we can spend fixing issues and adding new features, by not reporting duplicate issues.  Providing the following information will increase the chances of your issue being dealt with quickly:

* **Overview of the Issue** - if an error is being thrown a non-minified stack trace helps
* **Motivation for or Use Case** - explain why this is a bug for you
* **Version(s)** - is it a regression?
* **Related Issues** - has a similar issue been reported before?
* **Suggest a Fix** - if you can't fix the bug yourself, perhaps you can point to what might be causing the problem (line of code or commit)

**If you get help, help others. Good karma rulez!**

### Submitting a Pull Request
Before you submit your pull request consider the following guidelines:

* Search [GitHub][github] for an open or closed Pull Request that relates to your submission. You don't want to duplicate effort.
* Make your changes in a new git branch.  Don't ask us to pull from your main branch:

    ```shell
    git checkout -b my-fix-branch main
    ```
* **[PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)**.
* Consider our release cycle. We try to follow [SemVer v2.0.0](https://semver.org/). Randomly breaking public APIs is not an option.
* Create your patch, **including appropriate test cases**. You can run our test easily with:
   ```shell
   composer test
   ```
* Commit your changes using a descriptive commit message that follows our [commit message conventions](#commit-message-format). Adherence to the [commit message conventions](#commit-message-format) is required because release notes are automatically generated from these messages.

    ```shell
    git commit -s -a
    ```
  Note:  the `-s` flag will sign your changes and is mandatory. The optional commit `-a` command line option will automatically "add" and "rm" edited files.

* Push your branch to GitHub:

    ```shell
    git push origin my-fix-branch
    ```

* In GitHub, send a pull request to `main`.

That's it! Thank you for your contribution!

## <a name="commit"></a> Git Commit Guidelines

We have very precise rules over how our git commit messages can be formatted.  This leads to **more readable messages** that are easy to follow when looking through the **project history**.  But also, we use the git commit messages to **generate the change log**.

### Commit Message Format
Each commit message consists of a **header**, a **body** and a **footer**.  The header has a special format that includes a **type** a **subject**:

```
<type>: <subject>
<BLANK LINE>
<body>
<BLANK LINE>
<footer>
```

The **header** is mandatory.

Any line of the commit message cannot be longer 72 characters! This allows the message to be easier to read on GitHub as well as in various git tools.

### Revert
If the commit reverts a previous commit, it should begin with `revert: `, followed by the header of the reverted commit. In the body it should say: `This reverts commit <hash>.`, where the hash is the SHA of the commit being reverted.

### Type
Must be one of the following:

* **feat**: A new feature
* **fix**: A bug fix
* **doc**: Documentation only changes
* **style**: Changes that do not affect the meaning of the code (white-space, formatting, missing semi-colons, etc)
* **refactor**: A code change that neither fixes a bug nor adds a feature
* **test**: Adding missing tests

### Subject
The subject contains succinct description of the change:

* use the imperative, present tense: "change" not "changed" nor "changes"
* don't capitalize first letter
* no dot (.) at the end

### Body
Just as in the **subject**, use the imperative, present tense: "change" not "changed" nor "changes".
The body should include the motivation for the change and contrast this with previous behavior.

### Footer
The footer should contain any information about GitHub issues that this commit **Closes**.

Closed bugs should be listed on a separate line in the footer prefixed with "Closes" keyword like this:

```Closes #234```


[github]: https://github.com/pacoorozco/probind
