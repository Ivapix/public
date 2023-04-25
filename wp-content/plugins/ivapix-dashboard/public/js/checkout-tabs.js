document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.ivapix-domain-container').forEach((domain, index) => {
        domain.addEventListener('click', () => {
            domain.parentNode.classList.toggle('active')
        })
    })
})
