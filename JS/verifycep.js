const cep = document.querySelector('#cep');
const logradouro = document.querySelector('#logradouro');
const bairro = document.querySelector('#bairro');
const cidade = document.querySelector('#cidade');
const estado = document.querySelector('#estado');
const uf = document.querySelector('#uf');

cep.addEventListener('focusout', async () => {
  try {
    const onlyNumbers = /^[0-9]+$/;
    const cepValid = /^[0-9]{8}$/;

    // Remove o traço, se houver
    const cepSemMascara = cep.value.replace('-', '');

    if (!onlyNumbers.test(cepSemMascara) || !cepValid.test(cepSemMascara)) {
      throw { cep_error: 'CEP inválido' };
    }

    const response = await fetch(`https://viacep.com.br/ws/${cepSemMascara}/json/`);

    if (!response.ok) {
      throw await response.json();
    }

    const responseCep = await response.json();

    logradouro.value = responseCep.logradouro;
    bairro.value = responseCep.bairro;
    cidade.value = responseCep.localidade;
    estado.value = responseCep.estado;
    uf.value = responseCep.uf;

  } catch (error) {
    if (error?.cep_error) {
        console.log(cep_error);
      setTimeout(() => {
        console.log('');
      }, 5000);
    }
    console.log(error);
  }
});
