import http from 'k6/http';
import { check, sleep } from 'k6';


export let options = {
    vus: 50,
    duration: '60s',
};


export default function () {
    const payload = JSON.stringify({
        action: 'create_order',
        user_id: Math.floor(Math.random() * 100000),
        items: [{id:1, qty:1}],
    });


    const params = { headers: { 'Content-Type': 'application/json' } };
    let res = http.post('http://localhost:8080/api/orders', payload, params);
    check(res, { 'status was 200': (r) => r.status === 200 });
    sleep(0.1);
}
