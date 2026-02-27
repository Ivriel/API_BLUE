import http from 'k6/http';
import { check,sleep } from 'k6';

export const options = {
    vus: 100,// jumlah user virtual
    duration: '30s', // durasi test
};

export default function () {
    const res = http.get('http://localhost:8000/api/product-category');
    check(res, {
        'status code is 200': (r) => r.status === 200,
        'response time < 500ms': (r) => r.timings.duration < 300,
    });
    sleep(1); // delay biar ga terlalu bombardir
}