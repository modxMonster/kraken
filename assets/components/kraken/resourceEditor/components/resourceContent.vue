<template>
  <div class="krakenBlock">
    <h5>{{krakencontent.title}}</h5>
    <p class="m-0">{{krakencontent.description}}</p>
    <button class="selectScrollArea" v-on:click="scrollToElement()"></button>
    <div class="iconsLeft">
      <button class="btn editIcon" type="button" v-on:click="showEdit()">
        <i class="fas fa-edit"></i>
      </button>
      <content-editor
          :blockContent = "krakencontent"
          :id = "krakencontent.id">
      </content-editor>
      <button class="btn showHideIcon" type="button">
        <i class="far fa-eye"></i>
      </button>
    </div>
    <button class="btn removeIcon" type="button" v-on:click="deleteElement()">
      <i class="fas fa-times"></i>
    </button>
    <button class="btn moveIcon" type="button">
      <i class="fas fa-bars"></i>
    </button>
  </div>
</template>

<script>
module.exports = {
  name: "resource-content",
  components: {
    'content-editor': httpVueLoader('./contentEditor.vue'),
  },
  props: ['krakencontent'],
  methods: {
    showEdit() {
      if (this.$refs['edit-content' + this.krakencontent.id]) {
        this.$refs['edit-content' + this.krakencontent.id].show();
      } else {
        alert('Error opening: ' + 'edit-content' + this.krakencontent.id);
      }

    },
    deleteElement() {
      //First we confir that we actually want to delete the block
      //TODO this neds to be changed to use VueBoostrap's components
      const answer = confirm("You sure you want to proceed?");
      if (answer == true) {
        let finalObject = JSON.parse(JSON.stringify(this.krakencontent));
        finalObject['items'] = this.krakencontent['properties']['items'];
        delete finalObject['properties'];
        let axiosConfig = {
          headers: {
            'Content-Type': 'application/json',
            'User-Agent': 'Apache-HttpClient/4.1.1',
            "Access-Control-Allow-Origin": "*",
          }
        };
        // TODO reenable to use rigth api endpoint instead of this
        //  axios.post(window.location.protocol + "//" + window.location.host + '/modxMonster/rest/Resources/'
        axios.delete(window.location.protocol + "//" + window.location.host + Vue.prototype.$restRoute + '/rest/index.php?_rest=Resources/'
            + this.krakencontent.id,
            finalObject,
            axiosConfig)
            .then(response => {
              document.getElementById('demoIframe').src = document.getElementById('demoIframe').src;
              this.$emit('updated')
            })
            .catch(error => {
              console.log(error);
              this.showErrorAjax();
            });
      }
    },
    save(bvModalEvt) {
      let finalObject = JSON.parse(JSON.stringify(this.krakencontent));
      finalObject['items'] = this.krakencontent['properties']['items'];
      delete finalObject['properties'];
      let axiosConfig = {
        headers: {
          'Content-Type': 'application/json',
          'User-Agent': 'Apache-HttpClient/4.1.1',
          "Access-Control-Allow-Origin": "*",
        }
      };
      const modalRef = this.$bvModal;
      // TODO reenable to use rigth api endpoint instead of this
      //  axios.post(window.location.protocol + "//" + window.location.host + '/modxMonster/rest/Resources/'
      axios.post(window.location.protocol + "//" + window.location.host + Vue.prototype.$restRoute + '/rest/index.php?_rest=Resources/'
          + this.krakencontent.resourceId,
          finalObject,
          axiosConfig)
          .then(response => {
            modalRef.hide('add-content');
            document.getElementById('demoIframe').src = document.getElementById('demoIframe').src;
          })
          .catch(error => {
            console.log(error);
            this.showErrorAjax();
          });
    },
    scrollToElement() {
      const iframe = document.getElementById('demoIframe');
      var childDocument = iframe.contentDocument ? iframe.contentDocument : iframe.contentWindow.document;

      var selectedBlocks = childDocument.getElementsByClassName("blockSelected");
      while (selectedBlocks.length)
        selectedBlocks[0].className = selectedBlocks[0].className.replace(/\bblockSelected\b/g, "");

      const scrollToElement = childDocument.getElementById(this.krakencontent.id + '-' +
          this.krakencontent.title);
      if (!scrollToElement) {
        alert(this.krakencontent.id + '-' + this.krakencontent.title + " not present on DOM, please check your blocks!");
      } else {
        scrollToElement.className = "blockSelected";
        var rect = scrollToElement.getBoundingClientRect();
        childDocument.documentElement.scrollTop = rect.top;
      }
    }
  }
}
</script>

<style scoped>
.btn-outline-primary, .btn-check:focus+.btn, .btn:focus  {
  color: #525252;
  border-color: #525252;
}
.btn-check:focus+.btn, .btn:focus, .btn-check:active+.btn-outline-primary:focus, .btn-check:checked+.btn-outline-primary:focus, .btn-outline-primary.active:focus, .btn-outline-primary.dropdown-toggle.show:focus, .btn-outline-primary:active:focus {
  box-shadow: none !important;
}
.krakenBlock button {
  outline: none !important;
  border: 0 !important;
}
.krakenContainer, .krakenWrap {
  height: calc(100% - 35px);
  overflow-y: auto;
}
.krakenBlock {
  position: relative;
  padding: 0.5rem 3.2rem 0.5rem 48px;
  margin-bottom: .5rem;
  background: #f3f3f3;
  min-height: 5.5rem;
  overflow: hidden;
}
.krakenBlock h5 {
  position: absolute;
  top: 29px;
  width: 68%;
  line-height: 18px;
  font-size: 18px;
  margin: 0;
  overflow: hidden;
  max-height: 38px;
  transform: translateY(-50%);
}
.krakenBlock p {
  position: absolute;
  top: 64px;
  font-size: 14px;
  width: 72%;
  line-height: 14px;
  max-height: 30px;
  overflow: hidden;
  transform: translateY(-50%);
}
.krakenBlock button svg, .krakenBlock a svg{
  width: 20px;
  height: 20px;
  color: grey;
}
.removeIcon {
  position: absolute;
  right: 43px;
  top: 0;
}
.showHideIcon {
  position: absolute;
  bottom: 0;
  margin: 0;
}
.moveIcon {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  right: 0;
  background: #e8e8e8;
  height: 100%;
  width: 40px;
  padding: 0;
  color: #6b6b6b;
}
.editIcon {
  position: absolute;
  top: 0;
}
.iconsLeft {
  position: absolute;
  top: 5px;
  left: 2px;
  width: 33px;
  height: calc(100% - 10px);
  border-right: 1px solid lightgrey;
}
.krakenGhost {
  opacity: 0.5;
  background: #c1c1c1;
}
.krakenGhost h5, .krakenGhost p {
  color: black;
}
.selectScrollArea {
  position: absolute;
  width: calc(100% - 40px);
  height: 100%;
  top: 0;
  left: 0;
  border: navajowhite;
  background: transparent;
  padding: 0;
}

/*

.krakenWidth {
    max-width: 1980px;
}
button {
    border: none;
    outline: none;
}
.saveAddButtonsWrap {
    margin: 1rem 2.3rem 1rem 0;
}
.saveAddButtonsWrap button {
    background: none;
    border: 1px solid #b9b9b9;
    padding: .4rem 1.2rem;
}


 */
</style>