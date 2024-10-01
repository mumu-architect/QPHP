
// 首先获取Ras加密解密的证书：

//http://tool.chacuo.net/cryptrsakeyvalid/
// 以下是具体的代码：
/*<script src="https://cdn.bootcss.com/jsencrypt/3.0.0-beta.1/jsencrypt.js"></script>*/
  //  <script type="text/javascript">
//公钥
var PUBLIC_KEY = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCh5Nk2GLiyQFMIU+h3OEA4UeFbu3dCH5sjd/sLTxxvwjXq7JLqJbt2rCIdzpAXOi4jL+FRGQnHaxUlHUBZsojnCcHvhrz2knV6rXNogt0emL7f7ZMRo8IsQGV8mlKIC9xLnlOQQdRNUssmrROrCG99wpTRRNZjOmLvkcoXdeuaCQIDAQAB';
//私钥
var PRIVATE_KEY = 'MIICWwIBAAKBgQCh5Nk2GLiyQFMIU+h3OEA4UeFbu3dCH5sjd/sLTxxvwjXq7JLqJbt2rCIdzpAXOi4jL+FRGQnHaxUlHUBZsojnCcHvhrz2knV6rXNogt0emL7f7ZMRo8IsQGV8mlKIC9xLnlOQQdRNUssmrROrCG99wpTRRNZjOmLvkcoXdeuaCQIDAQABAoGAUTcJ1H6QYTOts9bMHsrERLymzir8R9qtLBzrfp/gRxxpigHGLdph8cWmk8dlN5HDRXmmkdV6t2S7xdOnzZen31lcWe0bIzg0SrFiUEOtg3Lwxzw2Pz0dKwg4ZUooGKpcIU6kEpbC2UkjBV4+2E6P1DXuhdgTyHoUA3ycxOdjCAUCQQCyjTzGPXFoHq5TmiJyVd4VXNyCXGU0ZuQayt6nPN8Gd5CcEb2S4kggzPXQcd90FO0kHfZV6+PGTrc2ZUuz5uwPAkEA6B3lmEmiZsJS/decLzWR0T1CXaFGwTjBQbHXJ0RziAfkuy+VwSmhvrW/ipk5xbREr5rKx3jVI2PzVOvLw7NgZwJAbUsvDFnH9WfyZZJPy5TsID97awCLoovozM2phM0p55eAmUfyttp0ND/BqBpMIY49qoH8q5N9FYJRe6Z9tF2B2QJAQBEocw039xcB4zCk2l713YQEEmXWarSomuJkWWFKZiyPlJ8Ava0pCMOPl8jNKmWkY7fc6ovOgJMw8aqXtm+HVwJAerJeUEDez2djG5pIF6aCV0bP3fhQUq8OQCgGF5Qzo9CnqvYreGpYKPJGVixAsEPCiLzJRhy1XfFona6VRXIIxw==';
//使用公钥加密
var encrypt = new JSEncrypt();
//encrypt.setPrivateKey('-----BEGIN RSA PRIVATE KEY-----'+PRIVATE_KEY+'-----END RSA PRIVATE KEY-----');
encrypt.setPublicKey('-----BEGIN PUBLIC KEY-----' + PUBLIC_KEY + '-----END PUBLIC KEY-----');
var str = {
    "uid":"1223334",
    "pwd":"asd"
}
var encrypted = encrypt.encrypt(JSON.stringify(str));
console.log('加密前数据:%o', str);
console.log('加密后数据:%o', encrypted);
//使用私钥解密
var decrypt = new JSEncrypt();
//decrypt.setPublicKey('-----BEGIN PUBLIC KEY-----' + PUBLIC_KEY + '-----END PUBLIC KEY-----');
decrypt.setPrivateKey('-----BEGIN RSA PRIVATE KEY-----'+PRIVATE_KEY+'-----END RSA PRIVATE KEY-----');
var uncrypted = decrypt.decrypt(encrypted);
console.log('解密后数据:%o', uncrypted);
   // </script>
