# PSTask1

Prestashop task

### Prerequisites

PHP 7.1+
Prestashop 1.7.5.2
MySQL/MariaDB

### Installing

1. install latest stable prestashop from their website
2. copy both dirs from the repo into `modules` dir
3. go to `admin`->`modules`->`modules catalog` search for `faq` and install the module `FAQ Extra`
4. click on `configure` and set the FAQ to the `left column`


## Getting Started

1. go to `admin`->`modules`->`FAQ Categories` create some categories
2. go to `admin`->`modules`->`FAQ Content` create some questions for each category
3. go to store frontend and open a category - the FAQ section should on the left after `Filter By`

## Tasks
do not lose a lot of time if there is something not clear or not working just ask in the chat - it is totally fine :)
1. fix the question answer - it is shown with the html tags as text instead of the tags being simple html
2. make the questions appear by the tree structure of the categories - first the main category's questions, then it's sub-category questions etc. - don't waste time for design: 

**example:** 

we have main category - cat1 with 2 questions cat1q1 and cat1q2, 

cat2 and cat 3 are subcategories of cat1.

cat2 has cat2q1 and cat2q2

cat3 has cat3q1 and cat3q2

cat4 is subcat of cat2

cat4 has cat4q1 and cat4q2

**the result should be like this:**

cat1q1

cat1q2

cat2q1

cat2q2

cat4q1

cat4q2

cat3q1

cat3q2

3. **optional task** make a button so we can enable/disable the  functionality from 2.
## Submission
create a repository in your favorite git place (github, bitbucket etc.) and send us  a link 



