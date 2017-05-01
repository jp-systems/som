<template>
  <div class="default">
    <div class="h75">
      <div class="header">
        <h1><md-icon class="md-size-5x">school</md-icon><br>Student Online Messenger</h1>
      </div>
      <div class="info">
        <div class="card">
          <md-icon class="md-size-5x">view_module</md-icon>
          <h1>All WBS Modules</h1>
          <p>All Worcester Business School modules are accesible, and information can be found about the module such as assignment details and the content of the course.</p>
        </div>
        <div class="card">
          <md-icon class="md-size-5x">chat</md-icon>
          <h1>Realtime Messaging</h1>
          <p>A realtime messaging platform, with a dedicated chat server running 24/7. Chat with fellow students about modules, with a chat channel for every WBS module.</p>
        </div>
        <div class="card">
          <md-icon class="md-size-5x">question_answer</md-icon>
          <h1>Questions & Answers</h1>
          <p>Ask and view questions related to each module, and reply and view answers to questions from other students. Rate answers depending on whether you found them helpful or not.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'Default',
  mounted () {
    // Go to Home view if already logged in
    if (this.$root.loggedIn) this.$router.replace('/home')
  }
}
</script>

<style lang="scss" scoped>
.default {
  width: 100%;
  height: 100%;

  > .h75 {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
    background-image: linear-gradient(45deg, #93a5cf 0%, #e4efe9 100%);
    overflow-y: auto;

    > .header {
      width: 100%;
      background-color: rgba(0, 0, 0, .1);
      box-shadow: 0 3px 44px rgba(255, 255, 255, .3);

      > h1 {
        text-align: center;
        font-size: 3rem;
        line-height: 3rem;
        text-shadow: 0 0 15px rgba(0, 0, 0, .2);
        color: white;
        padding: 4rem 0;
      }
    }

    > .info {
      display: flex;
      margin-top: auto;
      margin-bottom: auto;
      align-items: flex-start;
      justify-content: center;
      width: 100%;
      flex-shrink: 0;

      > .card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        color: rgba(60, 80, 90, .8);
        border: 1px solid rgba(60, 80, 90, .1);
        border-radius: 3px;
        margin: 1.5rem;
        padding: .5rem;
        flex-shrink: 0;

        > h1 {
          font-size: 1.2rem;
        }

        > p {
          font-size: .9rem;
          padding: .5rem 2rem;
          text-align: center;
        }
      }

      @media screen and (max-width: 600px) {
        flex-direction: column;
      }
    }
  }
}
</style>
