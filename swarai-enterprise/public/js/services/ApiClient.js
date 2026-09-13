export class ApiClient { async post(url, data) { return fetch(url, {method:'POST', body:data}); } }
