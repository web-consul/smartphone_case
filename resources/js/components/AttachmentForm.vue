<template>
  <div class="container">


    <div class="alert alert-success" role="alert" v-show="ok">Успешно</div>

    <form @submit.prevent="submit">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <div class="custom-file">
              <input
                type="file"
                class="custom-file-input"
                id="customFile"
                @change="onAttachmentChange"
              />
            </div>
          </div>
          <div class="alert alert-danger" v-if="errors && errors.attachment">
            {{ errors.attachment[0] }}
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <input
              type="text"
              class="form-control"
              placeholder="Название"
              v-model="name"
            />
          </div>
          <div class="alert alert-danger" v-if="errors && errors.name">
            {{ errors.name[0] }}
          </div>
        </div>

        <div class="col-md-2">
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
          </div>
        </div>
      </div>
    </form>

    <div v-for="image in images" :key="image.id">
      {{ image.name }}
      <img :src="/storage/ + image.path" width="100" />
    </div>
    <v-app>
      <v-pagination
        v-model="page"
          :length="total"
        :total-visible="total"
      ></v-pagination>
    </v-app>

  </div>
</template>

<script>
export default {
  data() {
    return {
      images: [],
      name: null,
      attachment: null,
      ok: false,
      errors: {},
      set_cookie: null,
      page: 1,
      total: 0,

    };
  },
    watch: {
    page: function () {
      this.imageUser();
    },
  },
  mounted() {
    this.imageUser();
  },

  methods: {
    imageUser: function () {
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios.get("/api/image/?page=" + this.page).then((response) => {
        this.images = response.data.data;
        this.page = response.data.meta.current_page;
        this.total = response.data.meta.last_page;
        });
      });
    },

    submit(event) {
      const config = { "content-type": "multipart/form-data" };
      const formData = new FormData();
      formData.append("name", this.name);
      formData.append("attachment", this.attachment);

      axios
        .post("/", formData, config)
        .then((response) => {
          this.ok = true;
          this.imageUser();
        })

        .catch((error) => {
          if (error.response.status == 422) {
            this.errors = error.response.data.errors;
          }
          console.log("Error");
        });

      this.errors = this.name = this.attachment = "";
      event.target.reset();
    },

    onAttachmentChange(e) {
      this.attachment = e.target.files[0];
    },
  },
};
</script>
