/**
 * Attaches a click event listener to a scroll-down button that, when triggered,
 * scrolls the page down by the height of the viewport minus the header height.
 *
 * @return {void} This function does not return a value.
 */
function handleScrollDown () {
    const scrollDownBtn = document.querySelector(".hero__scroll")
    const headerHeight = document.querySelector("header").offsetHeight

    scrollDownBtn.addEventListener("click", () => window.scrollBy(0, (window.innerHeight) - headerHeight))
}





document.addEventListener("DOMContentLoaded", ()=>{
    handleScrollDown()
})