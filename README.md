# Chimera - ReactPHP HTTP sample

> The term Chimera (_/kɪˈmɪərə/_ or _/kaɪˈmɪərə/_) has come to describe any
mythical or fictional animal with parts taken from various animals, or to
describe anything composed of very disparate parts, or perceived as wildly
imaginative, implausible, or dazzling.

There are many many amazing libraries in the PHP community and with the creation
and adoption of the PSRs we don't necessarily need to rely on full stack
frameworks to create a complex and well designed software. Choosing which
components to use and plugging them together can sometimes be a little
challenging.

The goal of this set of packages is to make it easier to do that (without
compromising the quality), allowing you to focus on the behaviour of your
software.

This project is a simple example of how to use the packages and what can be
achieved with them.

## Usage

You can simply clone (or use this package as skeleton) and run `composer run-script 
--timeout 0 serve`, which will expose the port `8080`.

### Endpoints

- **GET** /books: returns the entire book collection (can be optionally
filtered using `title` and `author` params on the query string)
- **POST** /book: appends a new book to the collection (receives a JSON object
with `title` and `author`, no fancy validation for now)
- **GET** /books/{id}: returns a book from the book collection
