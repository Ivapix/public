document.addEventListener("DOMContentLoaded", function() {
	const headTabs = ['dashboard', 'domeni', 'orders', 'details', 'address']

	headTabs.forEach(link => {
		document.getElementById(`vuvee-${link}-link`)?.addEventListener('click', () => {
			document.querySelectorAll('.ivapix-account-tabs').forEach(tab => {
				tab.classList.remove('active')
			})
			
			document.getElementById(`ivapix-${link}-tab`).classList.toggle('active')
			window.scrollBy(0, 299)
		})
	})
})