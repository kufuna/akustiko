function filterData() {
    let services = document.querySelectorAll('input[name="service"]:checked');
    let sectors = document.querySelectorAll('input[name="service"]:checked');

    let servicesVal = [];
    let sectorsVal = [];

    let values = [];

    if (services.length) {
        services.forEach((el) => {
            servicesVal.push(el.getAttribute('value'));
        });
        servicesVal = servicesVal.join(',');
        values.push('services='+servicesVal);
    }

    if (sectors.length) {
        sectors.forEach((el) => {
            sectorsVal.push(el.getAttribute('value'));
        });
        sectorsVal = sectorsVal.join(',');
        values.push('sectors='+sectorsVal);
    }

    let str = '';

    if (values.length) {
        str = values.join('&');
    }

    location.href = siteUrl + 'projects/1/' + (str ? '?'+str : '')
    console.log(servicesVal);
    return false;
}