/*
 *  Author:         Bruno Lima
 *  Date Created:   June 17, 2015
 *  Description:    Defines the Encryption module which handles all functions dealing
 *                  with encryption and decryption
 */

var Crypto = require('crypto');
var Xor = require('bitwise-xor');

var Encryption = function (dictionary)
{
    // Private Methods
    function generateSalt(lenght) 
    {
        // Generates a random salt that is encoded to a 32 character base64 string
        var set = '0123456789abcdefghijklmnopqurstuvwxyzABCDEFGHIJKLMNOPQURSTUVWXYZ';
        var set_length = set.length;
        var salt = '';
        for (var i = 0; i < lenght; i++) 
        {
            var random_index = Math.floor(Math.random() * set_length);
            salt += set[random_index];
        }
        return new Buffer(salt, 'utf8').toString('base64');
    }
    
    function pbkdf2(password, salt, count, key_length, raw_output)
    {
        // Creates a hash out of the original password and the random salt generated
        raw_output = typeof raw_output !== 'undefined' ? raw_output : false;
        
        var hash_hmac = Crypto.createHash('sha256');
        hash_hmac.write("");
        hash_hmac.end();
        var hash = hash_hmac.read();
                    
        var hash_length = hash.length;
        var block_count = Math.ceil(key_length / hash_length);
        var output = "";
        for(var i = 1; i <= block_count; i++)
        {
            
            var xor_sum = 0;
            
            // Add the current block number encoded as a 4 byte unsigned long (big endian) to the salt and set it as the root hash
            var current_hash = salt + new Buffer([((i >> 24) & 0xFF), ((i >> 16) & 0xFF), ((i >> 8) & 0xFF), (i & 0xFF)]).toString('utf8');
            
            current_hash = Crypto.createHmac('sha256', new Buffer(password, 'binary')).update(new Buffer(current_hash, 'binary')).digest();
            xor_sum = current_hash;
            
            for(var j = 1; j < count; j++)
            {
                current_hash = Crypto.createHmac('sha256', new Buffer(password, 'binary')).update(current_hash).digest();
                xor_sum = Xor(current_hash, xor_sum);
            }
            
            output += xor_sum.toString('binary');
        }
        if(raw_output)
        {
            return output.substr(0, key_length);
        }
        else
        {
            return parseInt(output.substr(0, key_length), 2).toString(16);
        }
                    
    }
    
    // Public Methods
    this.createHash = function(password)
    {
        // Generate a new hash (salt included) from a given password
        var salt = generateSalt(24);
        var hash_buffer = new Buffer(pbkfd2(password, salt, 1000, 24, true));
        return 'sha256:1000:' + salt + ':' + hash_buffer.toString('base64');
        
    }
    
    this.validatePassword = function(password, correct_hash)
    {
        // Validate that the given password generates the same hash as the hash given
        var params = correct_hash.split(':');
        if(params.length < 4)
           return false;
        var hash_buffer = new Buffer(params[3], 'base64');
        var hash = hash_buffer.toString('binary');
        var calculated_hash = pbkdf2(password, params[2], 1000, hash.length, true);
        return hash === calculated_hash;
    }
    
    return this;
};

module.exports = Encryption;