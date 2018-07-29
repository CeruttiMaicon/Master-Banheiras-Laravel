<template>
    <div>
        <!-- Div que deixa os elementos em linha (No bootstrap 4 deve ser adicionado form-inline-block ou form-inline-flex)-->
        <div class="form-inline-flex">
            <a v-if="criar" v-bind:href="criar">Criar</a>
            <!-- Pull Right - deixa os elementos a direita -->
            <div class="form-group pull-right">
                                                                                   <!-- Criando uma variavel -->
                <input type="search" class="form-controll" placeholder="Buscar..." v-model="buscar">
            </div>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="cursor:pointer" v-on:click="ordenaColuna(index)" v-for="(titulo, index) in titulos" :key="titulo">{{titulo}}</th>
                    <th v-if="detalhe || editar || deletar">Ação</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in lista" :key="index">
                    <!-- Aqui é feito o formulario onde o Vue faz um for pegando os itens passados no array -->
                    <td v-for="i in item" :key="i">{{i}}</td>
                    <!-- Ṣe um desses campos vier ele mostrara em tela -->
                    <td v-if="detalhe || editar || deletar">
                        <form v-bind:id="index" v-if="token && deletar" v-bind:action="deletar" method="post">
                            <!-- Campos ocultos para mandar o metodo e o token -->
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" v-bind:value="token">
                            <a v-if="detalhe" v-bind:href="detalhe">Detalhes |</a> 
                            <a v-if="editar"  v-bind:href="editar" > Editar |</a>
                            <!-- Funcao do botao delete -->
                            <a href="#" v-on:click="executaForm(index)"> Deletar </a>
                        </form>
                        <!-- Caso nao seja passado o token -->
                        <span v-if="!token">
                            <a v-if="detalhe" v-bind:href="detalhe">Detalhes |</a> 
                            <a v-if="editar"  v-bind:href="editar" > Editar |</a>
                            <a v-if="deletar"  v-bind:href="deletar" > Deletar</a>
                        </span>
                        <!-- Caso o token seja passado mas a rota de deletar nao -->
                        <span v-if="token && !deletar">
                            <a v-if="detalhe" v-bind:href="detalhe">Detalhes |</a> 
                            <a v-if="editar"  v-bind:href="editar" > Editar</a>
                        </span>
                    </td>   
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props:['titulos', 'itens', 'criar', 'detalhe', 'deletar', 'editar', 'token', 'ordem', 'ordemCol'],
        
        /*      
         *  Existem dois tipos de metodos para criar funções no Vue o computed e o methods
         *
         *          METHODS
         *      Os methos são execuados varias vezes enquanto se utiliza uma função e vai modificar alguma
         *  informação de uma listagem. Não é interessante utilizar o method para funções de listagem, mas
         *  você o utiliza-ra sempre que a ação for dada por parte do usuario.
         *      Ex: um botão onde o usuario terá a função do click para realizar a função, assim esse metodo não
         *  será utilizado toda a vez quando a pagina for atualizada e/ou estiver carregando uma lista
         *      
         *          COMPUTED
         *      O computed quando a função será repetida varias vezes e não tera uma interação do usuario
         *
         *      Abaixo a funcao de deletar que é utilizada acima
         */
        
        methods:{
            executaForm: function(index){
                document.getElementById('id').submit();
            },
            ordenaColuna: function(coluna) {
                this.ordemColAux = coluna;
                if(this.ordemAux.toLowerCase() == "asc"){
                    this.ordemAux = "desc";
                } else if (this.ordemAux.toLowerCase() == "desc"){
                    this.ordemAux = "asc"
                }
            }
        },

        computed:{
            lista:function(){

                //Criando as variaveis locais
                //Se for passado a tag ordem OU || valor padrao de ordenacao
                let ordem = this.ordemAux;
                let ordemCol = this.ordemColAux;

                //Transforma este valor em um número inteiro
                ordemCol = parseInt(ordemCol);
                // Para evitar problemas com letras maiusculas
                ordem = ordem.toLowerCase();

                /*
                 *        FUNÇÃO PARA ORDENAÇÃO DOS ITENS DA TABELA
                 *
                 *    sort() - É o que faz a ordenação, a a função de dentro funciona da seguinte maneira
                 *             O default do sort é crescente, ou se quiser personalizar deve ser passado
                 *             uma função, um parametro A e B, e com a função o seu retorno deve ser
                 *             ZERO ou um valor positivo ou um valor negativo.
                 *                
                 *                  (Ordenando desta maneira temos um retorn CRESCENTE)
                 *             Se A e B forem iguais, return ZERO
                 *             Se A > B, return numero positivo
                 *             Se A < B, return numero negativo
                 *
                 *                  (Ordenando desta maneira temos um retorn DECRESCENTE)
                 *             Se A e B forem iguais, return ZERO
                 *             Se A < B, return numero positivo
                 *             Se A > B, return numero negativo
                 */

                if(ordem == "asc"){
                    this.itens.sort(function(a,b){
                        if (a[ordemCol] > b[ordemCol]){
                            return 1;
                        } else if (a[ordemCol] < b[ordemCol]){
                            return -1;
                        } else {
                            return 0;
                        }
                    });
                } else {
                    this.itens.sort(function(a,b){
                        if (a[ordemCol] < b[ordemCol]){
                            return 1;
                        } else if (a[ordemCol] > b[ordemCol]){
                            return -1;
                        } else {
                            return 0;
                        }
                    });
                }

                /*     FILTRO DOS ITENS
                    Com o return true  - ele volta todos os itens
                    Com o return false - nenhum item

                    O filter faz um laço de repeticao com todos os itens
                */

                return this.itens.filter(res => {

                    /*     FUNÇÃO DE BUSCA
                     *   Se o valor encontrado for maior que zero é por que tem valores que se encaixam com a busca
                     *   toLowerCase() - Converte o valor do array em minusculos
                     *   indexOf()     - Faz a função de busca
                     *  
                     *   (res[i] + "") - Este trecho de ccódigo serve apenas para fazer a concatenação
                     *                   do valor com um vazio para transformalo em um tipo String
                     */ 

                    for(let i = 0; i < res.length; i++){
                        if((res[i] + "").toLowerCase().indexOf(this.buscar.toLowerCase()) >= 0){
                            return true;
                        } 
                    }
                    return false;
                });
                return this.itens;
            }
        },

        data: function() {
            return {
                buscar:'',
                ordemAux:this.ordem || 'asc',
                ordemColAux: this.ordemCol || 0,
            }
        }
    }
</script>