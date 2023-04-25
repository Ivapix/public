function mainRowExpanded(domainData) {
    const expandedRowContainer = document.createElement('div')
    const expandedRowHeading = document.createElement('h1')
    const expandedRowTable = document.createElement('table')
    const expandedRowTableRow1 = document.createElement('tr')
    const expandedRowTableRow2 = document.createElement('tr')
    const expandedRowTableCell1 = document.createElement('td')
    const expandedRowTableCell2 = document.createElement('td')
    const expandedRowTableCell3 = document.createElement('td')
    const expandedRowTableCell4 = document.createElement('td')
    const expandedRowList = document.createElement('div')
    const nameServerBtn = document.createElement('button')
    const whoisBtn = document.createElement('button')
    const lockBtn = document.createElement('button')
    const extendBtn = document.createElement('button')

    expandedRowContainer.className = 'vuvee-expanded-row'
    expandedRowHeading.textContent = domainData.domen
    expandedRowTable.setAttribute('cellpadding', 5)
    expandedRowTable.setAttribute('cellspacing', 0)
    expandedRowTable.setAttribute('border', 0)
    expandedRowTable.setAttribute('style', 'padding-left: 50px;')


    expandedRowTableCell1.textContent = 'Način plaćanja'
    expandedRowTableCell2.textContent = domainData.nacin_placanja

    expandedRowTableRow1.append(expandedRowTableCell1, expandedRowTableCell2)

    expandedRowTableCell3.textContent = 'Iznos za produženje'
    expandedRowTableCell4.textContent = domainData.iznos_za_produzavanje

    expandedRowTableRow2.append(expandedRowTableCell3, expandedRowTableCell4)

    expandedRowTable.append(expandedRowTableRow1, expandedRowTableRow2)

    expandedRowList.className = 'more-options-list'

    nameServerBtn.textContent = 'Promena name servera'
    nameServerBtn.onclick = () => {
        document.getElementById('vuvee-modal-backdrop-nameserver').classList.add('active')
    
        document.getElementById('name_server_1').value = domainData?.ns[0]
        document.getElementById('name_server_2').value = domainData?.ns[1]
        document.getElementById('name_server_3').value = domainData?.ns[2] ? domainData?.ns[2] : '' 
        document.getElementById('name_server_4').value = domainData?.ns[3] ? domainData?.ns[3] : ''
    }

    whoisBtn.textContent = 'Ažuriranje WHOIS podataka'

    whoisBtn.onclick = async () => {
        document.getElementById('vuvee-modal-backdrop').classList.add('active')

        const domainRes = await fetch(`/ivapix-staging/wp-admin/admin-ajax.php?action=domain_contact_endpoint&contact=${domainData?.registrant}`)
        const contactData = await domainRes.json()
        console.log(contactData)

        document.getElementById('domain_first_name').value = contactData.name
        // document.getElementById('domain_last_name').value = contactData.domain_last_name
        // document.getElementById('domain_pib_field').value = domainData.domain_pib_field
        // document.getElementById('domain_mb_field').value = domainData.domain_mb_field
        document.getElementById('domain_phone').value = contactData.voice
        document.getElementById('domain_email').value = domainData.registrant
        document.getElementById('domain_city').value = contactData.city
        document.getElementById('domain_country').value = contactData.country
        document.getElementById('domain_address').value = contactData.addressLine.join(', ')
    }

    lockBtn.textContent = 'Oktljučavanje / Zaključavanje domena'

    lockBtn.onclick = () => {
        document.getElementById('vuvee-modal-backdrop-lock').classList.add('active')
        
    }

    extendBtn.textContent = 'Transfer domena'

    extendBtn.onclick = () => {
        document.getElementById('vuvee-modal-backdrop-epp').classList.add('active')

        document.getElementById('epp_code').value = domainData.authcode
    }

    expandedRowList.append(nameServerBtn, whoisBtn, lockBtn, extendBtn)

    expandedRowContainer.append(expandedRowHeading, expandedRowTable, expandedRowList)

    return expandedRowContainer
}

jQuery(document).ready(function ($) {
    const inputs = document.querySelectorAll('.ivapix-form-control-column input')

    document.querySelectorAll('.close-vuvee-modal').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.vuvee-modal-backdrop').forEach(modal => {
                modal.classList.remove('active')
            })

            document.querySelector('div#vuvee-modal-backdrop h1').classList.remove('active')
            inputs.forEach(input => input.setAttribute('readonly', true))
        })
    })

    document.getElementById('vuvee-unlock').addEventListener('click', () => {
        document.querySelector('div.vuvee-modal-backdrop h1').classList.add('active')
        inputs.forEach(input => input.removeAttribute('readonly'))
    })

    const table = $('#ivapix-domain-table').DataTable({
        language: {
            processing: "Obrađivanje...",
            search: "Pretraži&nbsp;:",
            lengthMenu: "Broj _MENU_ redova",
            info: "Prikaz _END_ domena od _TOTAL_  mogućih",
            infoEmpty: "Nema kupljenih domena",
            infoFiltered: "Filtrirani podaci",
            infoPostFix: "",
            loadingRecords: "Učitavanje podataka...",
            zeroRecords: "Nema podataka u tabeli",
            emptyTable: "Nema podataka u tabeli",
            paginate: {
                first: "Prva strana",
                previous: "Prethodna strana",
                next: "Sledeća strana",
                last: "Poslednja strana"
            },
        },

        ajax: {
            url: "/wp-admin/admin-ajax.php?action=datatables_endpoint",
            cache: false,
            dataSrc: ''
        },

        columns: [
            {
                className: 'expand_row',
                orderable: false,
                data: null,
                defaultContent: '+',
            },
            { data: 'domainName' },
            { data: 'createdDate' },
            { data: 'expiryDate' },
            {
                data: 'privacyProtect',
                render: function (data, type, row) {
                    if (data) {
                        return `<span class="table-whois-active">Aktivna</span>`
                    } else {
                        return `<span class="table-whois-inactive">Neaktivna</span>`
                    }
                }
            },
            {
                data: 'status',
                render: function (data, type, row) {
                    if (data[0] === 'OK') {
                        return `<span class="table-whois-active">Aktivan</span>`
                    } else {
                        return `<span class="table-whois-inactive">Istekao</span>`
                    }
                }
            },
        ],
        order: [[1, 'asc']],
        pageLength: 10
    });

    $('#ivapix-domain-table tbody').on('click', 'td.expand_row', function () {
        const tr = $(this).closest('tr');
        const row = table.row(tr);

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
        } else {
            row.child(mainRowExpanded(row.data())).show();
            tr.addClass('shown');
        }
    });
});