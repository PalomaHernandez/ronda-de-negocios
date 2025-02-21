<template>
  <LayoutPage>
    <div class="w-full mx-auto p-6 bg-white rounded-lg shadow-md">
      <div v-if="success" class="alert alert-success" @click="authStore.clearMessages()">{{ success }}</div>
      <div v-if="error" class="alert alert-danger" @click="authStore.clearMessages()">{{ error }}</div>
      <h2 class="text-2xl font-semibold text-center mb-4">Editar Perfil</h2>

      <div class="flex flex-col items-center space-y-4">
        <!-- Imagen de perfil -->
        <div class="relative">
          <div class="w-24 h-24 flex items-center justify-center shadow-lg border rounded-full">
                     <img v-if="previewImage || form.logo_url" :src="previewImage || form.logo_url" alt="User Logo">
                    <i v-else class="fa-solid fa-circle-user text-gray-400 text-8xl"></i>
                 </div>
          <label
            class="absolute bottom-0 right-0 h-8 w-8 flex items-center justify-center bg-gray-800 text-white p-1 rounded-full cursor-pointer">
            <i class="fa-solid fa-camera"></i>
            <input type="file" @change="onFileChange" class="hidden" accept="image/*" />
          </label>
        </div>

        <!-- Campos del perfil -->
        <div class="w-full space-y-3">
          <LabeledObject required>
            <template #label>Nombre</template>
            <input type="text" v-model="form.name" required>
          </LabeledObject>
          <LabeledObject>
            <template #label>Email</template>
            <input type="email" v-model="form.email" disabled>
          </LabeledObject>
          <LabeledObject required>
            <template #label>Actividad/sector</template>
            <input type="text" v-model="form.activity" required>
          </LabeledObject>
          <LabeledObject required>
            <template #label>Ubicacion</template>
            <input type="text" v-model="form.location" required>
          </LabeledObject>
          <LabeledObject>
            <template #label>Sitio web</template>
            <input type="url" v-model="form.website" placeholder="https://">
          </LabeledObject>
          <div class="mt-6 p-4 bg-gray-100 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-2 text-gray-700">Tus intereses y ofertas en este evento
            </h3>
            <div class="grid grid-cols-2 gap-4">
              <LabeledObject>
                <template #label>Intereses</template>
                <input type="text" v-model="form.interests">
              </LabeledObject>
              <LabeledObject>
                <template #label>Productos/servicios</template>
                <input type="text" v-model="form.products_services">
              </LabeledObject>
            </div>
          </div>
          <LabeledObject>
            <template #label>Galeria</template>
            <ImageUploader type="gallery" :uploaded-files="user.images || []" @updateFiles="handleImagesUpdate"
              @deletedFiles="handleDeletedImages" />
          </LabeledObject>
        </div>

        <div class="flex justify-center space-x-4">
          <RouterLink :to="{ name: 'profile' }" class="btn">
            Cancelar
          </RouterLink>
          <button @click="guardarCambios"
            class="bg-blue-600 btn text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
            Guardar Cambios
          </button>
        </div>
      </div>
    </div>
  </LayoutPage>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import LayoutPage from "@/Layout.vue";
import ImageUploader from "@/components/ImageUploader.vue";
import LabeledObject from "@/components/LabeledObject.vue";

const authStore = useAuthStore();
const { success, error } = storeToRefs(authStore);
const user = computed(() => authStore.user ? authStore.user : {});
const registration = computed(() => authStore.registration ? authStore.registration : {});

const originalForm = ref({});

const form = ref({
  name: user.value.name || "",
  email: user.value.email || "",
  location: user.value.location || "",
  activity: user.value.activity || "",
  website: user.value.website || "",
  logo_url: user.value.logo_url || "",
  interests: registration.value.interests || "",
  products_services: registration.value.products_services || "",
});

onMounted(() => {
  originalForm.value = { ...form.value };
});

const previewImage = ref(null);

const gallery = ref([]);
const deleted_images = ref([]);
const selectedFile = ref(null);

const onFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    selectedFile.value = file;
    const reader = new FileReader();
    reader.onload = () => {
      previewImage.value = reader.result;
    };
    reader.readAsDataURL(file);
  }
};

const guardarCambios = async () => {
  try {

    const formData = new FormData();

    Object.keys(form.value).forEach((key) => {
      if (form.value[key] !== originalForm.value[key]) {
        formData.append(key, form.value[key]);
      }
    });

    if (previewImage.value) {
      formData.append("logo", selectedFile.value);
    }

    if (gallery.value.length > 0) {
      gallery.value.forEach((file, index) => {
        if (file instanceof File) {
          formData.append(`gallery[${index}]`, file);
        }
      });
    }

    if (deleted_images.value.length > 0) {
      deleted_images.value.forEach((img, index) => {
        formData.append(`deleted_images[${index}]`, img);
      });
    }

    if (formData.entries().next().done) {
      return;
    }

    await authStore.updateProfile(formData);
  } catch (error) {
    console.error("Error al actualizar perfil:", error);
  }
};

const handleImagesUpdate = (files) => {
  gallery.value = files;
};

const handleDeletedImages = (deleted) => {
  deleted_images.value = deleted;
};

</script>