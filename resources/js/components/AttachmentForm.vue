<template>
  <div class="container">
    <div class="alert alert-success" role="alert" v-show="ok">Успешно</div>
    <form @submit.prevent="submit">
      <div class="row">
        <div class="col-3">
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
        <div class="col-6">
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

        <div class="col-3">
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      name: null,
      attachment: null,
      ok: false,
      errors: {},
    };
  },
  methods: {
    submit() {
      const config = { "content-type": "multipart/form-data" };
      const formData = new FormData();
      formData.append("name", this.name);
      formData.append("attachment", this.attachment);

      axios
        .post("/", formData, config)
        .then((response) => {
          (this.ok = true), (this.name = null), (this.attachment = null);
        })

        .catch((error) => {
          if (error.response.status == 422) {
            this.errors = error.response.data.errors;
          }
          this.form_submitting = false;
          console.log("Error");
        });
    },
    onAttachmentChange(e) {
      this.attachment = e.target.files[0];
    },
  },
};
</script>
