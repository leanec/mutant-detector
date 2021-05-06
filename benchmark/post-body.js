const generatePostBody = async (userContext, events, done) => {
  try{
    const genes = ["A", "T", "C", "G"];
    let dna = [];
    for (i = 0; i < 6; i++) {
        let tmp = "";
        for (j = 0; j < 6; j++) {
            tmp += genes[Math.floor(Math.random() * 4)];
        }
        dna[i] = tmp;
    }

	const postBodyStr = {
        "dna":dna
    };

    userContext.vars.data = postBodyStr;

    return done();
  } catch(err) {
    console.log(`Error in generatePostBody: ${err}`);
    throw(err);
  }
}

module.exports.generatePostBody = generatePostBody;