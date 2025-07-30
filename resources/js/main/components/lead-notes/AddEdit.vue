<template>
    <a-modal
        :open="visible"
        :closable="false"
        :centered="true"
        :title="pageTitle"
        @ok="onSubmit"
    >
        <a-form layout="vertical">
            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-form-item
                    :label="$t('lead_notes.notes_typification_1')"
                    name="notes_typification_id_1"
                    :help="
                        rules.notes_typification_id_1
                            ? rules.notes_typification_id_1.message
                            : null
                    "
                    :validateStatus="rules.notes_typification_id_1 ? 'error' : null"
                >
                    <a-select
                        v-model:value="formData.notes_typification_id_1"
                        :placeholder="
                            $t('common.select_default_text', [
                                $t('lead_notes.notes_typification_1'),
                            ])
                        "
                        optionFilterProp="title"
                        show-search
                        :allowClear="true"
                        @change="
                            () => {
                                formData.notes_typification_id_2 = undefined;
                                formData.notes_typification_id_3 = undefined;
                                getChildTypification(formData.notes_typification_id_1);
                            }
                        "
                    >
                        <a-select-option
                            v-for="parentTypification in parentTypificationData"
                            :key="parentTypification.xid"
                            :title="parentTypification.name"
                            :value="parentTypification.xid"
                        >
                            {{ parentTypification.name }}
                        </a-select-option>
                    </a-select>
                </a-form-item>
            </a-col>
            <a-col
                :xs="24"
                :sm="24"
                :md="24"
                :lg="24"
                v-if="childrenTypificationData.length > 0"
            >
                <a-form-item
                    :label="$t('lead_notes.notes_typification_2')"
                    name="notes_typification_id_2"
                    :help="
                        rules.notes_typification_id_2
                            ? rules.notes_typification_id_2.message
                            : null
                    "
                    :validateStatus="rules.notes_typification_id_2 ? 'error' : null"
                >
                    <a-select
                        v-model:value="formData.notes_typification_id_2"
                        :placeholder="
                            $t('common.select_default_text', [
                                $t('lead_notes.notes_typification_2'),
                            ])
                        "
                        optionFilterProp="title"
                        show-search
                        :allowClear="true"
                        @change="
                            () => {
                                formData.notes_typification_id_3 = undefined;
                                getChildrenChildTypification(
                                    formData.notes_typification_id_2
                                );
                            }
                        "
                    >
                        <a-select-option
                            v-for="childrenTypification in childrenTypificationData"
                            :key="childrenTypification.xid"
                            :title="childrenTypification.name"
                            :value="childrenTypification.xid"
                        >
                            {{ childrenTypification.name }}
                        </a-select-option>
                    </a-select>
                </a-form-item>
            </a-col>
            <a-col
                :xs="24"
                :sm="24"
                :md="24"
                :lg="24"
                v-if="childrenChildData.length > 0"
            >
                <a-form-item
                    :label="$t('lead_notes.notes_typification_3')"
                    name="notes_typification_id_3"
                    :help="
                        rules.notes_typification_id_3
                            ? rules.notes_typification_id_3.message
                            : null
                    "
                    :validateStatus="rules.notes_typification_id_3 ? 'error' : null"
                    :allowClear="true"
                >
                    <a-select
                        v-model:value="formData.notes_typification_id_3"
                        :placeholder="
                            $t('common.select_default_text', [
                                $t('lead_notes.notes_typification_3'),
                            ])
                        "
                        optionFilterProp="title"
                        show-search
                        :allowClear="true"
                    >
                        <a-select-option
                            v-for="childrenChild in childrenChildData"
                            :key="childrenChild.xid"
                            :title="childrenChild.name"
                            :value="childrenChild.xid"
                        >
                            {{ childrenChild.name }}
                        </a-select-option>
                    </a-select>
                </a-form-item>
            </a-col>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('common.notes')"
                        name="notes"
                        :help="rules.notes ? rules.notes.message : null"
                        :validateStatus="rules.notes ? 'error' : null"
                        class="required"
                    >
                        <a-textarea
                            v-model:value="formData.notes"
                            :placeholder="
                                $t('common.placeholder_default_text', [
                                    $t('common.notes'),
                                ])
                            "
                            :rows="4"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('lead_notes.notes_file')"
                        name="notes_file"
                        :help="rules.notes_file ? rules.notes_file.message : null"
                        :validateStatus="rules.notes_file ? 'error' : null"
                    >
                        <UploadFile
                            :acceptFormat="'image/*,.pdf'"
                            :formData="formData"
                            folder="leadNotes"
                            uploadField="notes_file"
                            @onFileUploaded="
                                (file) => {
                                    formData.notes_file = file.file;
                                    formData.notes_file_url = file.file_url;
                                }
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
                <template #icon>
                    <SaveOutlined />
                </template>
                {{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
            </a-button>
            <a-button key="back" @click="onClose">
                {{ $t("common.cancel") }}
            </a-button>
        </template>
    </a-modal>
</template>
<script>
import { defineComponent, ref, computed, onMounted, watch } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import apiAdmin from "../../../common/composable/apiAdmin";
import UploadFile from "../../../common/core/ui/file/UploadFile.vue";
import { forEach } from "lodash-es";

export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        UploadFile,
    },
    setup(props, { emit }) {
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const notesTypifications = ref([]);
        const notesTypificationUrl = "notes-typifications?limit=10000";
        const parentTypificationData = ref([]);
        const childrenTypificationData = ref([]);
        const childrenChildData = ref([]);

        onMounted(() => {
            const notesTypificationPromise = axiosAdmin.get(notesTypificationUrl);

            Promise.all([notesTypificationPromise]).then(
                ([notesTypificationResponse]) => {
                    notesTypifications.value = notesTypificationResponse.data;
                    getParentTypification();
                }
            );
        });

        const getParentTypification = () => {
            parentTypificationData.value = [];

            forEach(notesTypifications.value, (parentData) => {
                if (parentData.x_parent_id == null) {
                    parentTypificationData.value.push(parentData);
                }
            });
        };

        const getChildTypification = (xid) => {
            childrenTypificationData.value = [];
            childrenChildData.value = [];

            if (xid !== undefined) {
                forEach(notesTypifications.value, (childrenData) => {
                    if (xid == childrenData.x_parent_id) {
                        childrenTypificationData.value.push(childrenData);
                    }
                });
            }
        };

        const getChildrenChildTypification = (xid) => {
            childrenChildData.value = [];

            if (xid !== undefined) {
                forEach(notesTypifications.value, (children) => {
                    if (xid == children.x_parent_id) {
                        childrenChildData.value.push(children);
                    }
                });
            }
        };

        const onSubmit = () => {
            addEditRequestAdmin({
                url: props.url,
                data: props.formData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };

        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        watch(
            () => props.visible,
            (newVal, oldVal) => {
                getParentTypification();
                childrenTypificationData.value = [];
                childrenChildData.value = [];

                if (newVal && props.addEditType == "edit") {
                    if (props.formData.notes_typification_id_1 != null) {
                        getChildTypification(props.formData.notes_typification_id_1);
                    }

                    if (props.formData.notes_typification_id_2 != null) {
                        getChildrenChildTypification(
                            props.formData.notes_typification_id_2
                        );
                    }
                }
            }
        );

        return {
            loading,
            rules,
            onClose,
            onSubmit,
            notesTypifications,
            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
            getParentTypification,
            parentTypificationData,
            getChildTypification,
            childrenTypificationData,
            getChildrenChildTypification,
            childrenChildData,
        };
    },
});
</script>
