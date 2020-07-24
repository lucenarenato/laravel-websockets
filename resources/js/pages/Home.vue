<template>
  <div>
    <div class="header">
      <div class="head">
        <img :src="src" alt="">
      </div>
      <div class="name">
        {{userid}}
      </div>
      <div class="background">
        <img :src="src" alt="">
      </div>
    </div>
    <div class="content">
      <mu-list>
        <mu-list-item button @click="changeAvatar">
          <mu-list-item-action>
            <mu-icon slot="left" value="send"/>
          </mu-list-item-action>
          <mu-list-item-title>Modificar avatar</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="handleTips">
          <mu-list-item-action>
            <mu-icon slot="left" value="inbox"/>
          </mu-list-item-action>
          <mu-list-item-title>Patrocinador</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="handleGithub">
          <mu-list-item-action>
            <mu-icon slot="left" value="grade"/>
          </mu-list-item-action>
          <mu-list-item-title>Github</mu-list-item-title>
        </mu-list-item>
        <mu-list-item button @click="rmLocalData">
          <mu-list-item-action>
            <mu-icon slot="left" value="drafts"/>
          </mu-list-item-action>
          <mu-list-item-title>Limpar cache</mu-list-item-title>
        </mu-list-item>
      </mu-list>
      <!--<mu-divider/>-->
    </div>
    <div class="logout">
      <mu-button @click="logout" class="demo-raised-button" full-width>Sair</mu-button>
    </div>
    <div style="height:80px"></div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import { clear, removeItem } from "../utils/localStorage";
import Confirm from "../components/Confirm";
import Alert from "../components/Alert";
export default {
  data() {
    return {};
  },
  async mounted() {
    this.$store.commit("setTab", true);
    if (!this.userid) {
      const data = await Confirm({
        title: "Pronto",
        content: "Você precisa fazer login para vê-lo",
        ok: "Ir para o login",
        cancel: "Voltar à página inicial"
      });
      if (data === "submit") {
        this.$router.push("/login");
        return;
      }
      this.$router.push("/");
    }
  },
  methods: {
    changeAvatar() {
      this.$router.push("/avatar");
      this.$store.commit("setTab", false);
    },
    async rmLocalData() {
      const data = await Confirm({
        title: "pronto",
        content: "A limpeza do cache fará com que o histórico de atualizações seja lembrado novamente."
      });
      if (data === "submit") {
        removeItem("update-20180916");
      }
    },
    async logout() {
      const data = await Confirm({
        title: "pronto",
        content: "Você tem coração para sair?"
      });
      if (data === "submit") {
        clear();
        this.$store.commit("setUserInfo", {
          type: "userid",
          value: ""
        });
        this.$store.commit("setUserInfo", {
          type: "token",
          value: ""
        });
        this.$store.commit("setUserInfo", {
          type: "src",
          value: ""
        });
        this.$store.commit("setUnread", {
          room1: 0,
          room2: 0
        });
        this.$router.push("/");
        this.$store.commit("setTab", false);
      }
    },
    handleGithub() {
      Alert({
        content: "https://github.com/nonfu/webchat"
      });
    },
    handleTips() {
      Alert({
        title: "Me compre uma xícara de café",
        html:
          '<div>' +
            '<img style="width: 200px;" src="https://xueyuanjun.com/wp-content/uploads/2019/05/e7156cfe0196dd7d7ea4f8f5f10b8d1a.jpeg" />' +
            '</div>'
      });
    }
  },
  computed: {
    ...mapState({
      userid: state => state.userInfo.userid,
      src: state => state.userInfo.src
    })
  }
};
</script>

<style lang="stylus" rel="stylesheet/stylus" scoped>
.header {
  position: relative;
  width: 100%;
  height: 200px;
  display: inline-block;

  .head {
    width: 80px;
    margin: 20px auto 0;

    img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
    }
  }

  .name {
    height: 50px;
    line-height: 50px;
    color: #ffffff;
    font-size: 18px;
    text-align: center;
  }

  .background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 200px;
    z-index: -1;
    filter: blur(10px);

    img {
      width: 100%;
      height: 100%;
    }
  }
}

.logout {
  width: 200px;
  margin: 0 auto;

  .mu-raised-button {
    background: #ff4081;
    color: #fff;
  }
}
</style>
