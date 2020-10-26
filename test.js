const header = {
    "typ": "JWT",
    "alg": "HS256"
};

// encode to base64
const encodedPayload = Buffer.from(JSON.stringify(header))
    .toString('base64')
    .replace('=', '');
console.log('payload: ',encodedPayload);
